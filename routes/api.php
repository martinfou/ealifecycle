<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StrategyController;
use App\Http\Controllers\Api\V1\PortfolioController;
use App\Http\Controllers\Api\V1\SymbolController;
use App\Http\Controllers\Api\V1\StrategyReportController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Token generation endpoint
Route::post('/v1/tokens', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token
    ]);
});

Route::middleware('auth:sanctum')->prefix('v1')->name('api.v1.')->group(function () {
    // Strategy Routes
    Route::apiResource('strategies', StrategyController::class);
    Route::post('strategies/{strategy}/source-code', [StrategyController::class, 'uploadSourceCode'])->name('strategies.uploadSourceCode');
    Route::get('strategies/{strategy}/source-code', [StrategyController::class, 'downloadSourceCode'])->name('strategies.downloadSourceCode');

    // Portfolio Routes
    Route::apiResource('portfolios', PortfolioController::class);
    
    // Symbol Routes
    Route::apiResource('symbols', SymbolController::class);

    // Strategy Reports - API specific routes
    Route::prefix('strategies/{strategy}/reports')->name('strategies.reports.')->group(function () {
        Route::post('/', [StrategyReportController::class, 'upload'])->name('upload');
        Route::get('/', [StrategyReportController::class, 'download'])->name('download');
        Route::delete('/', [StrategyReportController::class, 'delete'])->name('delete');
    });
}); 