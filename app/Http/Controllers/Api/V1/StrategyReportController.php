<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Strategy;
use App\Models\StrategyReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @OA\Tag(
 *     name="Strategy Reports",
 *     description="API Endpoints for managing strategy PDF reports"
 * )
 */
class StrategyReportController extends Controller
{
    /**
     * Upload a new PDF report for a strategy
     * 
     * @OA\Post(
     *     path="/api/v1/strategies/{strategy}/reports",
     *     summary="Upload a new PDF report",
     *     tags={"Strategy Reports"},
     *     @OA\Parameter(
     *         name="strategy",
     *         in="path",
     *         required=true,
     *         description="Strategy ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="report",
     *                     type="file",
     *                     format="binary",
     *                     description="PDF report file (max 10MB)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Report uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Report uploaded successfully"),
     *             @OA\Property(property="filename", type="string"),
     *             @OA\Property(property="original_filename", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function upload(Strategy $strategy, Request $request): JsonResponse
    {
        if (!$strategy->canUserEdit(auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'report' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $file = $request->file('report');
        $originalFilename = $file->getClientOriginalName();
        $filename = Str::uuid() . '.pdf';
        $path = $file->storeAs("strategies/{$strategy->id}/reports", $filename, 'public');

        // Delete existing report if any
        if ($strategy->report) {
            Storage::disk('public')->delete($strategy->report->file_path);
            $strategy->report->delete();
        }

        // Create new report record
        $strategy->report()->create([
            'file_path' => $path,
            'original_filename' => $originalFilename,
            'uploaded_by' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Report uploaded successfully',
            'filename' => $filename,
            'original_filename' => $originalFilename
        ], 201);
    }

    /**
     * Download a specific report
     * 
     * @OA\Get(
     *     path="/api/v1/strategies/{strategy}/reports",
     *     summary="Download the strategy's report",
     *     tags={"Strategy Reports"},
     *     @OA\Parameter(
     *         name="strategy",
     *         in="path",
     *         required=true,
     *         description="Strategy ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PDF file download",
     *         @OA\MediaType(
     *             mediaType="application/pdf",
     *             @OA\Schema(type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Report not found"
     *     )
     * )
     */
    public function download(Strategy $strategy): StreamedResponse|JsonResponse
    {
        if (!$strategy->canUserView(auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$strategy->report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        if (!Storage::disk('public')->exists($strategy->report->file_path)) {
            return response()->json(['message' => 'Report file not found'], 404);
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $strategy->report->original_filename . '"',
        ];

        return response()->streamDownload(function () use ($strategy) {
            echo Storage::disk('public')->get($strategy->report->file_path);
        }, $strategy->report->original_filename, $headers);
    }

    /**
     * Delete a report
     * 
     * @OA\Delete(
     *     path="/api/v1/strategies/{strategy}/reports",
     *     summary="Delete the strategy's report",
     *     tags={"Strategy Reports"},
     *     @OA\Parameter(
     *         name="strategy",
     *         in="path",
     *         required=true,
     *         description="Strategy ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Report deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Report deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Report not found"
     *     )
     * )
     */
    public function delete(Strategy $strategy): JsonResponse
    {
        if (!$strategy->canUserEdit(auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$strategy->report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        Storage::disk('public')->delete($strategy->report->file_path);
        $strategy->report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }
} 