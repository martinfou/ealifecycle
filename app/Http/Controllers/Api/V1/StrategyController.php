<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Strategy;
use Illuminate\Http\Request;
use App\Http\Resources\StrategyResource;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Timeframe;

/**
 * @OA\Tag(
 *     name="Strategies",
 *     description="API Endpoints for Managing Strategies"
 * )
 */
class StrategyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/strategies",
     *     summary="Get list of strategies",
     *     tags={"Strategies"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="List of strategies")
     * )
     */
    public function index()
    {
        $user = Auth::user();
        $accessibleGroupIds = $user->getAccessibleGroupIds();

        $strategies = Strategy::where('user_id', $user->id)
                                ->orWhereIn('group_id', $accessibleGroupIds)
                                ->with(['status', 'timeframes', 'user', 'group'])
                                ->latest()
                                ->paginate(20);

        return StrategyResource::collection($strategies);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/strategies",
     *     summary="Create a new strategy",
     *     tags={"Strategies"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","timeframe_names","primary_timeframe_name","status_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="symbols_traded", type="string"),
     *             @OA\Property(property="magic_number", type="integer"),
     *             @OA\Property(property="timeframe_names", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="primary_timeframe_name", type="string"),
     *             @OA\Property(property="group_id", type="integer"),
     *             @OA\Property(property="status_name", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Strategy created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'symbols_traded' => 'nullable|string',
            'magic_number' => 'nullable|integer|unique:strategies,magic_number',
            'timeframe_names' => 'required|array|min:1',
            'timeframe_names.*' => 'exists:timeframes,name',
            'primary_timeframe_name' => 'required|exists:timeframes,name',
            'group_id' => 'nullable|exists:groups,id',
            'status_name' => 'required|exists:statuses,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if (isset($validated['group_id']) && !$user->hasWritePermissionInGroup($validated['group_id'])) {
            return response()->json(['message' => 'You do not have permission to create a strategy in this group.'], 403);
        }
        
        $validated['user_id'] = $user->id;
        $validated['date_in_status'] = now();

        // Get status ID from name
        $status = Status::where('name', $validated['status_name'])->first();
        $validated['status_id'] = $status->id;
        unset($validated['status_name']);

        $strategy = DB::transaction(function () use ($validated) {
            $strategyData = $validated;
            unset($strategyData['timeframe_names'], $strategyData['primary_timeframe_name']);
            
            $strategy = Strategy::create($strategyData);

            // Get timeframe IDs from names
            $timeframes = Timeframe::findByNames($validated['timeframe_names']);
            $primaryTimeframe = Timeframe::findByName($validated['primary_timeframe_name']);

            $timeframeData = [];
            foreach ($timeframes as $timeframe) {
                $timeframeData[$timeframe->id] = ['is_primary' => ($timeframe->id === $primaryTimeframe->id)];
            }
            $strategy->timeframes()->sync($timeframeData);

            $strategy->statusHistory()->create([
                'new_status_id' => $strategy->status_id,
                'changed_by_user_id' => $strategy->user_id,
            ]);

            return $strategy;
        });

        return (new StrategyResource($strategy->load(['status', 'timeframes', 'user', 'group'])))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/strategies/{id}",
     *     summary="Get a specific strategy",
     *     tags={"Strategies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Strategy details"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function show(Strategy $strategy)
    {
        if (!$strategy->canUserView(Auth::user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $strategy->load(['status', 'timeframes', 'user', 'group']);

        return new StrategyResource($strategy);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/strategies/{id}",
     *     summary="Update a strategy",
     *     tags={"Strategies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","timeframe_names","primary_timeframe_name","status_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="symbols_traded", type="string"),
     *             @OA\Property(property="magic_number", type="integer"),
     *             @OA\Property(property="timeframe_names", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="primary_timeframe_name", type="string"),
     *             @OA\Property(property="group_id", type="integer"),
     *             @OA\Property(property="status_name", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Strategy updated"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, Strategy $strategy)
    {
        $user = Auth::user();

        if (!$strategy->canUserEdit($user)) {
            return response()->json(['message' => 'You do not have permission to edit this strategy.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'symbols_traded' => 'nullable|string',
            'magic_number' => 'nullable|integer|unique:strategies,magic_number,' . $strategy->id,
            'timeframe_names' => 'required|array|min:1',
            'timeframe_names.*' => 'exists:timeframes,name',
            'primary_timeframe_name' => 'required|exists:timeframes,name',
            'group_id' => 'nullable|exists:groups,id',
            'status_name' => 'required|exists:statuses,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $validated = $validator->validated();

        if (isset($validated['group_id']) && !$user->hasWritePermissionInGroup($validated['group_id'])) {
            return response()->json(['message' => 'You do not have permission to assign a strategy to this group.'], 403);
        }

        $strategy = DB::transaction(function () use ($strategy, $validated) {
            $strategyData = $validated;
            unset($strategyData['timeframe_names'], $strategyData['primary_timeframe_name']);
            
            $strategy->update($strategyData);

            // Get timeframe IDs from names
            $timeframes = Timeframe::findByNames($validated['timeframe_names']);
            $primaryTimeframe = Timeframe::findByName($validated['primary_timeframe_name']);

            $timeframeData = [];
            foreach ($timeframes as $timeframe) {
                $timeframeData[$timeframe->id] = ['is_primary' => ($timeframe->id === $primaryTimeframe->id)];
            }
            $strategy->timeframes()->sync($timeframeData);

            if ($strategy->wasChanged('status_id')) {
                $strategy->statusHistory()->create([
                    'new_status_id' => $strategy->status_id,
                    'changed_by_user_id' => Auth::id(),
                ]);
            }

            return $strategy;
        });

        return new StrategyResource($strategy->load(['status', 'timeframes', 'user', 'group']));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/strategies/{id}",
     *     summary="Delete a strategy",
     *     tags={"Strategies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Deleted successfully"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function destroy(Strategy $strategy)
    {
        $user = Auth::user();

        if (!$strategy->canUserEdit($user)) {
            return response()->json(['message' => 'You do not have permission to delete this strategy.'], 403);
        }

        DB::transaction(function () use ($strategy) {
            // Delete source code file if it exists
            if ($strategy->source_code_path) {
                Storage::disk('public')->delete($strategy->source_code_path);
            }

            // Detach timeframes
            $strategy->timeframes()->detach();

            // Delete status history
            $strategy->statusHistory()->delete();

            // Delete strategy
            $strategy->delete();
        });

        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/strategies/{id}/source-code",
     *     summary="Upload source code for a strategy",
     *     tags={"Strategies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="source_code_file", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="File uploaded"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function uploadSourceCode(Request $request, Strategy $strategy)
    {
        $user = Auth::user();

        if (!$strategy->canUserEdit($user)) {
            return response()->json(['message' => 'You do not have permission to upload a file for this strategy.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'source_code_file' => 'required|file|mimes:mq4,mq5,ex4,ex5|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('source_code_file');

        // Delete old file if it exists
        if ($strategy->source_code_path) {
            Storage::disk('public')->delete($strategy->source_code_path);
        }

        $path = $file->store('strategy_source_codes', 'public');
        
        $strategy->update([
            'source_code_path' => $path,
            'source_code_original_filename' => $file->getClientOriginalName(),
        ]);

        return new StrategyResource($strategy);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/strategies/{id}/source-code",
     *     summary="Download source code for a strategy",
     *     tags={"Strategies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="File download"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="File not found")
     * )
     */
    public function downloadSourceCode(Strategy $strategy)
    {
        $user = Auth::user();

        if (!$strategy->canUserView($user)) {
            return response()->json(['message' => 'You do not have permission to download this file.'], 403);
        }

        if (!$strategy->source_code_path || !Storage::disk('public')->exists($strategy->source_code_path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        $path = Storage::disk('public')->path($strategy->source_code_path);
        $filename = $strategy->source_code_original_filename ?? basename($strategy->source_code_path);
        $mimeType = Storage::disk('public')->mimeType($strategy->source_code_path);

        return response()->download(
            $path, 
            $filename, 
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache'
            ]
        );
    }
}
