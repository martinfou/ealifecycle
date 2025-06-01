<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use App\Models\Status;
use App\Models\Timeframe;
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
        $strategies = Strategy::where('user_id', Auth::id())
            ->with(['status', 'timeframe'])
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
        
        return view('strategies.create', compact('statuses', 'timeframes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbols_traded' => 'nullable|string',
            'timeframe_id' => 'required|exists:timeframes,id',
            'magic_number' => 'nullable|integer|unique:strategies,magic_number',
            'status_id' => 'required|exists:statuses,id',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $strategy = Strategy::create([
                'name' => $request->name,
                'symbols_traded' => $request->symbols_traded,
                'timeframe_id' => $request->timeframe_id,
                'magic_number' => $request->magic_number,
                'status_id' => $request->status_id,
                'date_in_status' => now()->toDateString(),
                'user_id' => Auth::id(),
                'description' => $request->description,
            ]);

            // Record initial status in history
            StatusHistory::create([
                'strategy_id' => $strategy->id,
                'previous_status_id' => null,
                'new_status_id' => $request->status_id,
                'changed_by_user_id' => Auth::id(),
                'notes' => 'Strategy created',
            ]);
        });

        return redirect()->route('strategies.index')
            ->with('success', 'Strategy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Strategy $strategy)
    {
        $this->authorize('view', $strategy);
        
        $strategy->load(['status', 'timeframe', 'user', 'trades' => function ($query) {
            $query->orderBy('open_time', 'desc')->limit(10);
        }]);

        return view('strategies.show', compact('strategy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Strategy $strategy)
    {
        $this->authorize('update', $strategy);
        
        $statuses = Status::where('is_active', true)->get();
        $timeframes = Timeframe::where('is_active', true)->ordered()->get();
        
        return view('strategies.edit', compact('strategy', 'statuses', 'timeframes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Strategy $strategy)
    {
        $this->authorize('update', $strategy);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'symbols_traded' => 'nullable|string',
            'timeframe_id' => 'required|exists:timeframes,id',
            'magic_number' => 'nullable|integer|unique:strategies,magic_number,' . $strategy->id,
            'description' => 'nullable|string',
        ]);

        $strategy->update([
            'name' => $request->name,
            'symbols_traded' => $request->symbols_traded,
            'timeframe_id' => $request->timeframe_id,
            'magic_number' => $request->magic_number,
            'description' => $request->description,
        ]);

        return redirect()->route('strategies.index')
            ->with('success', 'Strategy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Strategy $strategy)
    {
        $this->authorize('delete', $strategy);
        
        $strategy->delete();

        return redirect()->route('strategies.index')
            ->with('success', 'Strategy deleted successfully.');
    }

    public function changeStatus(Request $request, Strategy $strategy)
    {
        $this->authorize('update', $strategy);
        
        $request->validate([
            'status_id' => 'required|exists:statuses,id',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $strategy) {
            $previousStatusId = $strategy->status_id;
            
            $strategy->update([
                'status_id' => $request->status_id,
                'date_in_status' => now()->toDateString(),
            ]);

            StatusHistory::create([
                'strategy_id' => $strategy->id,
                'previous_status_id' => $previousStatusId,
                'new_status_id' => $request->status_id,
                'changed_by_user_id' => Auth::id(),
                'notes' => $request->notes,
            ]);
        });

        return redirect()->back()
            ->with('success', 'Strategy status updated successfully.');
    }

    public function history(Strategy $strategy)
    {
        $this->authorize('view', $strategy);
        
        $history = $strategy->statusHistory()
            ->with(['previousStatus', 'newStatus', 'changedByUser'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('strategies.history', compact('strategy', 'history'));
    }
}
