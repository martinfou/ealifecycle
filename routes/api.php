<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\StrategyController;
use App\Http\Controllers\Api\V1\PortfolioController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->prefix('v1')->name('api.v1.')->group(function () {
    // Strategy Routes
    Route::apiResource('strategies', StrategyController::class);
    Route::post('strategies/{strategy}/source-code', [StrategyController::class, 'uploadSourceCode'])->name('strategies.uploadSourceCode');
    Route::get('strategies/{strategy}/source-code', [StrategyController::class, 'downloadSourceCode'])->name('strategies.downloadSourceCode');

    // Portfolio Routes
    Route::apiResource('portfolios', PortfolioController::class);
}); 