<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Symbol;
use App\Http\Resources\SymbolResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Symbols",
 *     description="API Endpoints for Managing Trading Symbols"
 * )
 */
class SymbolController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/symbols",
     *     summary="Get a list of symbols",
     *     tags={"Symbols"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="A paginated list of symbols", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SymbolResource")))
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $symbols = Symbol::paginate($perPage);
        return SymbolResource::collection($symbols);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/symbols",
     *     summary="Create a new symbol",
     *     tags={"Symbols"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code", "symbol"},
     *             @OA\Property(property="code", type="string", example="EURUSD"),
     *             @OA\Property(property="symbol", type="string", example="Euro / US Dollar")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Symbol created successfully", @OA\JsonContent(ref="#/components/schemas/SymbolResource")),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:symbols,code|max:255',
            'symbol' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $symbol = Symbol::create($validator->validated());

        return (new SymbolResource($symbol))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/symbols/{id}",
     *     summary="Get a specific symbol",
     *     tags={"Symbols"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Symbol details", @OA\JsonContent(ref="#/components/schemas/SymbolResource")),
     *     @OA\Response(response=404, description="Symbol not found")
     * )
     */
    public function show(Symbol $symbol)
    {
        return new SymbolResource($symbol);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/symbols/{id}",
     *     summary="Update a symbol",
     *     tags={"Symbols"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code", "symbol"},
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="symbol", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Symbol updated successfully", @OA\JsonContent(ref="#/components/schemas/SymbolResource")),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=404, description="Symbol not found")
     * )
     */
    public function update(Request $request, Symbol $symbol)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:symbols,code,' . $symbol->id . '|max:255',
            'symbol' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $symbol->update($validator->validated());

        return new SymbolResource($symbol);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/symbols/{id}",
     *     summary="Delete a symbol",
     *     tags={"Symbols"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Symbol deleted successfully"),
     *     @OA\Response(response=404, description="Symbol not found")
     * )
     */
    public function destroy(Symbol $symbol)
    {
        $symbol->delete();

        return response()->noContent();
    }
} 