<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use App\Models\Status;
use App\Models\Timeframe;
use App\Models\Group;
use App\Models\StatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StrategyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $accessibleGroupIds = $user->getAccessibleGroupIds();
        
        $strategies = Strategy::with(['status', 'timeframe', 'group'])
            ->where(function ($query) use ($user, $accessibleGroupIds) {
                $query->where('user_id', $user->id) // Own strategies
                      ->orWhereIn('group_id', $accessibleGroupIds); // Group strategies
            })
            ->orderBy('updated_at', 'desc')
            ->get();

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
            'timeframe_id' => 'required|exists:timeframes,id',
            'magic_number' => 'nullable|integer|unique:strategies,magic_number',
            'status_id' => 'required|exists:statuses,id',
            'group_id' => 'nullable|exists:groups,id',
            'description' => 'nullable|string',
        ]);

        // Verify user has write permission in the selected group
        if ($validated['group_id']) {
            if (!$user->hasWritePermissionInGroup($validated['group_id'])) {
                return back()->withErrors(['group_id' => 'You do not have write permission in the selected group.']);
            }
        }

        $validated['user_id'] = $user->id;
        $validated['date_in_status'] = now();

        DB::transaction(function () use ($validated) {
            $strategy = Strategy::create($validated);

            // Create initial status history entry
            StatusHistory::create([
                'strategy_id' => $strategy->id,
                'previous_status_id' => null,
                'new_status_id' => $strategy->status_id,
                'changed_by_user_id' => $strategy->user_id,
                'notes' => 'Strategy created',
            ]);
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

        $strategy->load(['status', 'timeframe', 'group', 'user', 'statusHistory.previousStatus', 'statusHistory.newStatus', 'statusHistory.changedByUser']);
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
            'timeframe_id' => 'required|exists:timeframes,id',
            'magic_number' => 'nullable|integer|unique:strategies,magic_number,' . $strategy->id,
            'group_id' => 'nullable|exists:groups,id',
            'description' => 'nullable|string',
        ]);

        // Verify user has write permission in the new group (if changing groups)
        if ($validated['group_id'] && $validated['group_id'] != $strategy->group_id) {
            if (!$user->hasWritePermissionInGroup($validated['group_id'])) {
                return back()->withErrors(['group_id' => 'You do not have write permission in the selected group.']);
            }
        }

        $strategy->update($validated);

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

        $strategy->load(['status', 'timeframe', 'group', 'user']);
        $statusHistory = $strategy->statusHistory()
            ->with(['previousStatus', 'newStatus', 'changedByUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('strategies.history', compact('strategy', 'statusHistory'));
    }
}
