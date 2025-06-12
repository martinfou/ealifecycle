<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use App\Models\Status;
use App\Models\StatusHistory;
use App\Models\Timeframe;
use App\Models\Group;
use App\Models\StrategyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StrategyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        $strategies = Strategy::with(['status', 'timeframes', 'group', 'user'])
            ->accessibleByUser($user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('strategies.index', compact('strategies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = Status::where('is_active', true)->get();
        $timeframes = Timeframe::where('is_active', true)->ordered()->get();
        $groups = Auth::user()->groups()->get(); // Only groups user belongs to
        
        return view('strategies.create', compact('statuses', 'timeframes', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbols_traded' => 'nullable|string',
            'timeframe_ids' => 'required|array|min:1',
            'timeframe_ids.*' => 'exists:timeframes,id',
            'primary_timeframe_id' => 'required|exists:timeframes,id',
            'magic_number' => 'nullable|integer|unique:strategies,magic_number',
            'status_id' => 'required|exists:statuses,id',
            'group_id' => 'nullable|exists:groups,id',
            'description' => 'nullable|string',
            'source_code_file' => 'nullable|file|max:2048',
            'report_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Custom validation for file extension
        if ($request->hasFile('source_code_file')) {
            $file = $request->file('source_code_file');
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['mq4', 'mq5', 'ex4', 'ex5'];
            if (!in_array($extension, $allowedExtensions)) {
                return back()->withErrors(['source_code_file' => 'The source code file must be a file of type: mq4, mq5, ex4, ex5.'])->withInput();
            }

            $path = $file->store('strategy_source_codes', 'public');
            $validated['source_code_path'] = $path;
            $validated['source_code_original_filename'] = $file->getClientOriginalName();
        }

        // Ensure primary timeframe is in the selected timeframes
        if (!in_array($validated['primary_timeframe_id'], $validated['timeframe_ids'])) {
            return back()->withErrors(['primary_timeframe_id' => 'Primary timeframe must be one of the selected timeframes.']);
        }

        // Verify user has write permission in the selected group
        if ($validated['group_id']) {
            if (!$user->hasWritePermissionInGroup($validated['group_id'])) {
                return back()->withErrors(['group_id' => 'You do not have write permission in the selected group.']);
            }
        }

        $validated['user_id'] = $user->id;
        $validated['date_in_status'] = now();

        DB::transaction(function () use ($validated, $request, $user) {
            // Create strategy without timeframe data
            $strategyData = $validated;
            unset($strategyData['timeframe_ids'], $strategyData['primary_timeframe_id']);
            $strategy = Strategy::create($strategyData);

            // Attach timeframes with primary flag
            $timeframeData = [];
            foreach ($validated['timeframe_ids'] as $timeframeId) {
                $timeframeData[$timeframeId] = [
                    'is_primary' => $timeframeId == $validated['primary_timeframe_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $strategy->timeframes()->attach($timeframeData);

            // Create initial status history entry
            StatusHistory::create([
                'strategy_id' => $strategy->id,
                'previous_status_id' => null,
                'new_status_id' => $strategy->status_id,
                'changed_by_user_id' => $strategy->user_id,
                'notes' => 'Strategy created',
            ]);

            // Handle PDF upload
            if ($request->hasFile('report_pdf')) {
                $file = $request->file('report_pdf');
                $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('reports', $filename, 'public'); // store in storage/app/public/reports
                StrategyReport::create([
                    'strategy_id' => $strategy->id,
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'uploaded_by' => $user->id,
                ]);
            }
        });

        return redirect()->route('strategies.index')->with('success', 'Strategy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Strategy $strategy)
    {
        $user = Auth::user();
        
        // Check if user can view this strategy
        if (!$strategy->canUserView($user)) {
            abort(403, 'You do not have permission to view this strategy.');
        }

        $strategy->load(['status', 'timeframes', 'group', 'user', 'statusHistory.previousStatus', 'statusHistory.newStatus', 'statusHistory.changedByUser', 'report.uploader']);
        $statuses = Status::where('is_active', true)->get();
        $canEdit = $strategy->canUserEdit($user);
        
        return view('strategies.show', compact('strategy', 'statuses', 'canEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Strategy $strategy)
    {
        $user = Auth::user();
        
        // Check if user can edit this strategy
        if (!$strategy->canUserEdit($user)) {
            abort(403, 'You do not have permission to edit this strategy.');
        }

        $statuses = Status::where('is_active', true)->get();
        $timeframes = Timeframe::where('is_active', true)->ordered()->get();
        $groups = $user->writeGroups()->get(); // Only groups with write permission
        
        return view('strategies.edit', compact('strategy', 'statuses', 'timeframes', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Strategy $strategy)
    {
        $user = Auth::user();
        
        // Check if user can edit this strategy
        if (!$strategy->canUserEdit($user)) {
            abort(403, 'You do not have permission to edit this strategy.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbols_traded' => 'nullable|string',
            'timeframe_ids' => 'required|array|min:1',
            'timeframe_ids.*' => 'exists:timeframes,id',
            'primary_timeframe_id' => 'required|exists:timeframes,id',
            'magic_number' => 'nullable|integer|unique:strategies,magic_number,' . $strategy->id,
            'group_id' => 'nullable|exists:groups,id',
            'description' => 'nullable|string',
            'source_code_file' => 'nullable|file|max:2048',
            'report_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Custom validation for file extension
        if ($request->hasFile('source_code_file')) {
            $file = $request->file('source_code_file');
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['mq4', 'mq5', 'ex4', 'ex5'];
            if (!in_array($extension, $allowedExtensions)) {
                return back()->withErrors(['source_code_file' => 'The source code file must be a file of type: mq4, mq5, ex4, ex5.'])->withInput();
            }
            
            // Delete the old file if it exists
            if ($strategy->source_code_path) {
                Storage::disk('public')->delete($strategy->source_code_path);
            }
            $path = $file->store('strategy_source_codes', 'public');
            $validated['source_code_path'] = $path;
            $validated['source_code_original_filename'] = $file->getClientOriginalName();
        }

        // Ensure primary timeframe is in the selected timeframes
        if (!in_array($validated['primary_timeframe_id'], $validated['timeframe_ids'])) {
            return back()->withErrors(['primary_timeframe_id' => 'Primary timeframe must be one of the selected timeframes.']);
        }

        // Verify user has write permission in the new group (if changing groups)
        if ($validated['group_id'] && $validated['group_id'] != $strategy->group_id) {
            if (!$user->hasWritePermissionInGroup($validated['group_id'])) {
                return back()->withErrors(['group_id' => 'You do not have write permission in the selected group.']);
            }
        }

        DB::transaction(function () use ($validated, $request, $strategy, $user) {
            // Update strategy without timeframe data
            $strategyData = $validated;
            unset($strategyData['timeframe_ids'], $strategyData['primary_timeframe_id']);
            $strategy->update($strategyData);

            // Sync timeframes with primary flag
            $timeframeData = [];
            foreach ($validated['timeframe_ids'] as $timeframeId) {
                $timeframeData[$timeframeId] = [
                    'is_primary' => $timeframeId == $validated['primary_timeframe_id'],
                    'updated_at' => now(),
                ];
            }
            $strategy->timeframes()->sync($timeframeData);

            // Handle PDF upload
            if ($request->hasFile('report_pdf')) {
                // Delete old report if it exists
                $oldReport = $strategy->report;
                if ($oldReport) {
                    \Storage::disk('public')->delete($oldReport->file_path);
                    $oldReport->delete();
                }
                $file = $request->file('report_pdf');
                $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('reports', $filename, 'public'); // store in storage/app/public/reports
                StrategyReport::create([
                    'strategy_id' => $strategy->id,
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'uploaded_by' => $user->id,
                ]);
            }
        });

        return redirect()->route('strategies.show', $strategy)->with('success', 'Strategy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Strategy $strategy)
    {
        $user = Auth::user();
        
        // Check if user can edit (delete) this strategy
        if (!$strategy->canUserEdit($user)) {
            abort(403, 'You do not have permission to delete this strategy.');
        }

        $strategy->delete();

        return redirect()->route('strategies.index')->with('success', 'Strategy deleted successfully.');
    }

    /**
     * Change the status of a strategy.
     */
    public function changeStatus(Request $request, Strategy $strategy)
    {
        $user = Auth::user();
        
        // Check if user can edit this strategy
        if (!$strategy->canUserEdit($user)) {
            abort(403, 'You do not have permission to change the status of this strategy.');
        }

        $request->validate([
            'status_id' => 'required|exists:statuses,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($strategy->status_id == $request->status_id) {
            return back()->with('error', 'Strategy is already in the selected status.');
        }

        DB::transaction(function () use ($request, $strategy, $user) {
            $previousStatusId = $strategy->status_id;
            
            $strategy->update([
                'status_id' => $request->status_id,
                'date_in_status' => now(),
            ]);

            StatusHistory::create([
                'strategy_id' => $strategy->id,
                'previous_status_id' => $previousStatusId,
                'new_status_id' => $request->status_id,
                'changed_by_user_id' => $user->id,
                'notes' => $request->notes,
            ]);
        });

        return back()->with('success', 'Strategy status updated successfully.');
    }

    /**
     * Show the status history of a strategy.
     */
    public function history(Strategy $strategy)
    {
        $user = Auth::user();
        
        // Check if user can view this strategy
        if (!$strategy->canUserView($user)) {
            abort(403, 'You do not have permission to view this strategy.');
        }

        $strategy->load(['status', 'timeframes', 'group', 'user']);
        $statusHistory = $strategy->statusHistory()
            ->with(['previousStatus', 'newStatus', 'changedByUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('strategies.history', compact('strategy', 'statusHistory'));
    }

    /**
     * Download the source code file for the specified strategy.
     */
    public function downloadSourceCode(Strategy $strategy)
    {
        $user = Auth::user();

        // Check if user can view this strategy
        if (!$strategy->canUserView($user)) {
            abort(403, 'You do not have permission to download this file.');
        }

        // Check if the file exists
        if (!$strategy->source_code_path || !Storage::disk('public')->exists($strategy->source_code_path)) {
            abort(404, 'File not found.');
        }

        $path = Storage::disk('public')->path($strategy->source_code_path);
        $filename = $strategy->source_code_original_filename ?? basename($strategy->source_code_path);

        return response()->download($path, $filename);
    }

    public function uploadReport(Request $request, Strategy $strategy)
    {
        $user = Auth::user();
        if (!$strategy->canUserView($user)) {
            abort(403, 'You do not have permission to upload a report for this strategy.');
        }
        $validated = $request->validate([
            'report_pdf' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);
        // Delete old report if it exists
        $oldReport = $strategy->report;
        if ($oldReport) {
            \Storage::disk('public')->delete($oldReport->file_path);
            $oldReport->delete();
        }
        $file = $request->file('report_pdf');
        $filename = \Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('reports', $filename, 'public');
        $newReport = \App\Models\StrategyReport::create([
            'strategy_id' => $strategy->id,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'uploaded_by' => $user->id,
        ]);
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'filename' => $newReport->original_filename,
                'download_url' => route('strategies.downloadReport', [$strategy, $newReport]),
                'view_url' => route('strategies.viewReport', [$strategy, $newReport]),
                'delete_url' => route('strategies.deleteReport', [$strategy, $newReport]),
                'uploaded_by' => $user->name,
                'created_at' => $newReport->created_at->format('M j, Y g:i A'),
                'can_edit' => $strategy->canUserEdit($user),
            ]);
        }
        return redirect()->route('strategies.show', $strategy)->with('success', 'Report uploaded successfully.');
    }

    public function deleteReport(Strategy $strategy, \App\Models\StrategyReport $report)
    {
        $user = \Auth::user();
        if (!$strategy->canUserEdit($user) || $report->strategy_id !== $strategy->id) {
            abort(403, 'You do not have permission to delete this report.');
        }
        \Storage::disk('public')->delete($report->file_path);
        $report->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('strategies.show', $strategy)->with('success', 'Report deleted successfully.');
    }

    public function downloadReport(Strategy $strategy, StrategyReport $report)
    {
        $user = Auth::user();
        if (!$strategy->canUserView($user) || $report->strategy_id !== $strategy->id) {
            abort(403, 'You do not have permission to download this report.');
        }
        return Storage::disk('public')->download($report->file_path, $report->original_filename);
    }

    public function viewReport(Strategy $strategy, StrategyReport $report)
    {
        $user = Auth::user();
        if (!$strategy->canUserView($user) || $report->strategy_id !== $strategy->id) {
            abort(403, 'You do not have permission to view this report.');
        }
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $report->original_filename . '"',
        ];
        return response()->file(storage_path('app/public/' . $report->file_path), $headers);
    }
}
