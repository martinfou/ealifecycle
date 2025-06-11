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

/**
 * @OA\Tag(
 *     name="Portfolios",
 *     description="API Endpoints for Managing Portfolios"
 * )
 */
class PortfolioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/portfolios",
     *     summary="Get list of portfolios",
     *     tags={"Portfolios"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="List of portfolios")
     * )
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
     * @OA\Post(
     *     path="/api/v1/portfolios",
     *     summary="Create a new portfolio",
     *     tags={"Portfolios"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="group_id", type="integer"),
     *             @OA\Property(property="strategy_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Portfolio created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Get(
     *     path="/api/v1/portfolios/{id}",
     *     summary="Get a specific portfolio",
     *     tags={"Portfolios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Portfolio details"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
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
     * @OA\Put(
     *     path="/api/v1/portfolios/{id}",
     *     summary="Update a portfolio",
     *     tags={"Portfolios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="group_id", type="integer"),
     *             @OA\Property(property="strategy_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Portfolio updated"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Delete(
     *     path="/api/v1/portfolios/{id}",
     *     summary="Delete a portfolio",
     *     tags={"Portfolios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Deleted successfully"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
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
