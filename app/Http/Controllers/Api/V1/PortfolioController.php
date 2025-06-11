<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Resources\PortfolioResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Strategy;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $accessibleGroupIds = $user->getAccessibleGroupIds();

        $portfolios = Portfolio::where('user_id', $user->id)
                                 ->orWhereIn('group_id', $accessibleGroupIds)
                                 ->with(['user', 'group'])
                                 ->latest()
                                 ->paginate(20);

        return PortfolioResource::collection($portfolios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
            'strategy_ids' => 'nullable|array',
            'strategy_ids.*' => 'exists:strategies,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if (isset($validated['group_id']) && !$user->hasWritePermissionInGroup($validated['group_id'])) {
            return response()->json(['message' => 'You do not have permission to create a portfolio in this group.'], 403);
        }

        // Check if user can view all provided strategies
        if (isset($validated['strategy_ids'])) {
            foreach ($validated['strategy_ids'] as $strategyId) {
                $strategy = Strategy::find($strategyId);
                if (!$strategy || !$strategy->canUserView($user)) {
                    return response()->json(['message' => "You do not have permission to view one or more of the selected strategies."], 403);
                }
            }
        }
        
        $validated['user_id'] = $user->id;

        $portfolio = DB::transaction(function () use ($validated) {
            $portfolioData = $validated;
            unset($portfolioData['strategy_ids']);

            $portfolio = Portfolio::create($portfolioData);

            if (isset($validated['strategy_ids'])) {
                $portfolio->strategies()->sync($validated['strategy_ids']);
            }

            return $portfolio;
        });

        return (new PortfolioResource($portfolio->load(['user', 'group', 'strategies'])))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Portfolio $portfolio)
    {
        if (!$portfolio->canUserView(Auth::user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $portfolio->load(['user', 'group', 'strategies']);

        return new PortfolioResource($portfolio);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $user = Auth::user();

        if (!$portfolio->canUserEdit($user)) {
            return response()->json(['message' => 'You do not have permission to edit this portfolio.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
            'strategy_ids' => 'nullable|array',
            'strategy_ids.*' => 'exists:strategies,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if (isset($validated['group_id']) && !$user->hasWritePermissionInGroup($validated['group_id'])) {
            return response()->json(['message' => 'You do not have permission to assign a portfolio to this group.'], 403);
        }

        if (isset($validated['strategy_ids'])) {
            foreach ($validated['strategy_ids'] as $strategyId) {
                $strategy = Strategy::find($strategyId);
                if (!$strategy || !$strategy->canUserView($user)) {
                    return response()->json(['message' => "You do not have permission to use one or more of the selected strategies."], 403);
                }
            }
        }

        DB::transaction(function () use ($validated, $portfolio) {
            $portfolioData = $validated;
            unset($portfolioData['strategy_ids']);

            $portfolio->update($portfolioData);

            if (isset($validated['strategy_ids'])) {
                $portfolio->strategies()->sync($validated['strategy_ids']);
            } else {
                $portfolio->strategies()->detach();
            }
        });

        return new PortfolioResource($portfolio->load(['user', 'group', 'strategies']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        $user = Auth::user();

        if (!$portfolio->canUserEdit($user)) {
            return response()->json(['message' => 'You do not have permission to delete this portfolio.'], 403);
        }

        DB::transaction(function () use ($portfolio) {
            $portfolio->strategies()->detach();
            $portfolio->delete();
        });

        return response()->json(null, 204);
    }
}
