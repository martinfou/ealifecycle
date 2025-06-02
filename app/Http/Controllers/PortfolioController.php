<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Strategy;
use App\Models\PortfolioHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolios = Portfolio::forUser(Auth::id())
                               ->with(['strategies' => function ($query) {
                                   $query->whereIn('portfolio_strategies.status', ['active', 'paused']);
                               }])
                               ->orderByDesc('created_at')
                               ->paginate(10);

        return view('portfolios.index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('portfolios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_capital' => 'required|numeric|min:0',
            'status' => 'required|in:active,paused,archived',
        ]);

        $validated['user_id'] = Auth::id();

        $portfolio = Portfolio::create($validated);

        // Log portfolio creation
        PortfolioHistory::logActivity(
            portfolioId: $portfolio->id,
            actionType: 'created',
            userId: Auth::id(),
            newValues: [
                'name' => $portfolio->name,
                'initial_capital' => $portfolio->initial_capital,
                'status' => $portfolio->status,
            ]
        );

        return redirect()->route('portfolios.show', $portfolio)
                        ->with('success', 'Portfolio created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Portfolio $portfolio)
    {
        // Check if user can view this portfolio
        if (!$portfolio->canUserView(Auth::user())) {
            abort(403, 'You do not have permission to view this portfolio.');
        }

        $portfolio->load([
            'strategies' => function ($query) {
                $query->whereIn('portfolio_strategies.status', ['active', 'paused'])
                      ->with(['status', 'timeframes']);
            }
        ]);

        return view('portfolios.show', compact('portfolio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portfolio $portfolio)
    {
        // Check if user can edit this portfolio
        if (!$portfolio->canUserEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this portfolio.');
        }

        return view('portfolios.edit', compact('portfolio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        // Check if user can edit this portfolio
        if (!$portfolio->canUserEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this portfolio.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_capital' => 'required|numeric|min:0',
            'status' => 'required|in:active,paused,archived',
        ]);

        // Store old values for history
        $oldValues = [
            'name' => $portfolio->name,
            'description' => $portfolio->description,
            'initial_capital' => $portfolio->initial_capital,
            'status' => $portfolio->status,
        ];

        $portfolio->update($validated);

        // Log portfolio update
        $changedFields = [];
        foreach ($validated as $key => $value) {
            if ($oldValues[$key] !== $value) {
                $changedFields[$key] = $value;
            }
        }

        if (!empty($changedFields)) {
            $actionType = isset($changedFields['status']) ? 'status_changed' : 'updated';
            
            PortfolioHistory::logActivity(
                portfolioId: $portfolio->id,
                actionType: $actionType,
                userId: Auth::id(),
                oldValues: $oldValues,
                newValues: $validated
            );
        }

        return redirect()->route('portfolios.show', $portfolio)
                        ->with('success', 'Portfolio updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        // Check if user can edit this portfolio
        if (!$portfolio->canUserEdit(Auth::user())) {
            abort(403, 'You do not have permission to delete this portfolio.');
        }

        $portfolio->delete();

        return redirect()->route('portfolios.index')
                        ->with('success', 'Portfolio deleted successfully!');
    }

    /**
     * Show the form for adding strategies to the portfolio.
     */
    public function addStrategies(Portfolio $portfolio)
    {
        // Check if user can edit this portfolio
        if (!$portfolio->canUserEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this portfolio.');
        }

        // Get IDs of strategies that are currently active or paused in this portfolio
        $activePortfolioStrategyIds = $portfolio->strategies()
                                                ->whereIn('portfolio_strategies.status', ['active', 'paused'])
                                                ->pluck('strategies.id')
                                                ->toArray();

        // Get strategies user can access that are not currently active/paused in this portfolio
        $availableStrategies = Auth::user()->strategies()
                                          ->whereNotIn('id', $activePortfolioStrategyIds)
                                          ->with(['status', 'timeframes'])
                                          ->orderBy('name')
                                          ->get();

        return view('portfolios.add-strategies', compact('portfolio', 'availableStrategies'));
    }

    /**
     * Add strategies to the portfolio.
     */
    public function storeStrategies(Request $request, Portfolio $portfolio)
    {
        // Check if user can edit this portfolio
        if (!$portfolio->canUserEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this portfolio.');
        }

        $validated = $request->validate([
            'strategies' => 'required|array|min:1',
            'strategies.*.strategy_id' => 'required|exists:strategies,id',
            'strategies.*.allocation_amount' => 'nullable|numeric|min:0',
            'strategies.*.allocation_percent' => 'nullable|numeric|min:0|max:100',
            'strategies.*.notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $portfolio) {
            foreach ($validated['strategies'] as $strategyData) {
                $strategyId = $strategyData['strategy_id'];
                
                // Check if this strategy was previously in the portfolio
                $existingRelation = $portfolio->strategies()
                    ->wherePivot('strategy_id', $strategyId)
                    ->first();
                
                $allocationData = [
                    'allocation_amount' => $strategyData['allocation_amount'] ?? 0,
                    'allocation_percent' => $strategyData['allocation_percent'] ?? 0,
                    'status' => 'active',
                    'date_added' => now()->toDateString(),
                    'notes' => $strategyData['notes'],
                ];
                
                if ($existingRelation) {
                    // Update existing relationship (re-activate removed strategy)
                    $portfolio->strategies()->updateExistingPivot($strategyId, array_merge($allocationData, [
                        'date_removed' => null,
                    ]));
                    
                    // Log strategy re-activation
                    PortfolioHistory::logActivity(
                        portfolioId: $portfolio->id,
                        actionType: 'strategy_activated',
                        userId: Auth::id(),
                        strategyId: $strategyId,
                        newValues: $allocationData
                    );
                } else {
                    // Create new relationship
                    $portfolio->strategies()->attach($strategyId, $allocationData);
                    
                    // Log strategy addition
                    PortfolioHistory::logActivity(
                        portfolioId: $portfolio->id,
                        actionType: 'strategy_added',
                        userId: Auth::id(),
                        strategyId: $strategyId,
                        newValues: $allocationData
                    );
                }
            }
        });

        return redirect()->route('portfolios.show', $portfolio)
                        ->with('success', 'Strategies added to portfolio successfully!');
    }

    /**
     * Update a strategy's allocation in the portfolio.
     */
    public function updateStrategyAllocation(Request $request, Portfolio $portfolio, Strategy $strategy)
    {
        // Check if user can edit this portfolio
        if (!$portfolio->canUserEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this portfolio.');
        }

        $validated = $request->validate([
            'allocation_amount' => 'nullable|numeric|min:0',
            'allocation_percent' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,paused',
            'notes' => 'nullable|string',
        ]);

        // Get current values for history
        $currentRelation = $portfolio->strategies()->where('strategies.id', $strategy->id)->first();
        $oldValues = [
            'allocation_amount' => $currentRelation->pivot->allocation_amount,
            'allocation_percent' => $currentRelation->pivot->allocation_percent,
            'status' => $currentRelation->pivot->status,
            'notes' => $currentRelation->pivot->notes,
        ];

        $portfolio->strategies()->updateExistingPivot($strategy->id, $validated);

        // Log the change
        $actionType = $oldValues['status'] !== $validated['status'] 
            ? ($validated['status'] === 'paused' ? 'strategy_paused' : 'strategy_activated')
            : 'strategy_updated';

        PortfolioHistory::logActivity(
            portfolioId: $portfolio->id,
            actionType: $actionType,
            userId: Auth::id(),
            strategyId: $strategy->id,
            oldValues: $oldValues,
            newValues: $validated
        );

        return redirect()->route('portfolios.show', $portfolio)
                        ->with('success', 'Strategy allocation updated successfully!');
    }

    /**
     * Remove a strategy from the portfolio.
     */
    public function removeStrategy(Portfolio $portfolio, Strategy $strategy)
    {
        // Check if user can edit this portfolio
        if (!$portfolio->canUserEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this portfolio.');
        }

        // Get current values for history
        $currentRelation = $portfolio->strategies()->where('strategies.id', $strategy->id)->first();
        $oldValues = [
            'allocation_amount' => $currentRelation->pivot->allocation_amount,
            'allocation_percent' => $currentRelation->pivot->allocation_percent,
            'status' => $currentRelation->pivot->status,
            'notes' => $currentRelation->pivot->notes,
        ];

        $portfolio->strategies()->updateExistingPivot($strategy->id, [
            'status' => 'removed',
            'date_removed' => now()->toDateString(),
        ]);

        // Log strategy removal
        PortfolioHistory::logActivity(
            portfolioId: $portfolio->id,
            actionType: 'strategy_removed',
            userId: Auth::id(),
            strategyId: $strategy->id,
            oldValues: $oldValues,
            newValues: ['status' => 'removed', 'date_removed' => now()->toDateString()]
        );

        return redirect()->route('portfolios.show', $portfolio)
                        ->with('success', 'Strategy removed from portfolio successfully!');
    }

    /**
     * Show the portfolio history.
     */
    public function history(Portfolio $portfolio)
    {
        // Check if user can view this portfolio
        if (!$portfolio->canUserView(Auth::user())) {
            abort(403, 'You do not have permission to view this portfolio.');
        }

        $history = $portfolio->history()
                            ->with(['strategy', 'user'])
                            ->paginate(20);

        return view('portfolios.history', compact('portfolio', 'history'));
    }
}
