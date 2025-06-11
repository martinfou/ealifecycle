<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\TimeframeController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TokenController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // API Token management
    Route::get('/tokens', [TokenController::class, 'index'])->name('tokens.index');
    Route::post('/tokens', [TokenController::class, 'store'])->name('tokens.store');
    Route::delete('/tokens/{token}', [TokenController::class, 'destroy'])->name('tokens.destroy');
    
    // Strategy management
    Route::resource('strategies', StrategyController::class);
    Route::get('strategies/{strategy}/download', [StrategyController::class, 'downloadSourceCode'])->name('strategies.downloadSourceCode');
    Route::post('strategies/{strategy}/change-status', [StrategyController::class, 'changeStatus'])->name('strategies.change-status');
    Route::get('strategies/{strategy}/history', [StrategyController::class, 'history'])->name('strategies.history');
    
    // Portfolio management
    Route::resource('portfolios', PortfolioController::class);
    Route::get('portfolios/{portfolio}/history', [PortfolioController::class, 'history'])->name('portfolios.history');
    Route::get('portfolios/{portfolio}/add-strategies', [PortfolioController::class, 'addStrategies'])->name('portfolios.add-strategies');
    Route::post('portfolios/{portfolio}/add-strategies', [PortfolioController::class, 'storeStrategies'])->name('portfolios.store-strategies');
    Route::patch('portfolios/{portfolio}/strategies/{strategy}', [PortfolioController::class, 'updateStrategyAllocation'])->name('portfolios.update-strategy-allocation');
    Route::delete('portfolios/{portfolio}/strategies/{strategy}', [PortfolioController::class, 'removeStrategy'])->name('portfolios.remove-strategy');
    
    // Trade management
    Route::get('trades', [TradeController::class, 'index'])->name('trades.index');
    Route::get('trades/import', [TradeController::class, 'import'])->name('trades.import');
    Route::post('trades/import', [TradeController::class, 'processImport'])->name('trades.process-import');
    Route::get('trades/{trade}', [TradeController::class, 'show'])->name('trades.show');
    
    // Admin routes (protected by middleware)
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        // Status management
        Route::resource('statuses', StatusController::class);
        
        // Timeframe management
        Route::resource('timeframes', TimeframeController::class);
        
        // Group management
        Route::resource('groups', GroupController::class);
        Route::post('groups/{group}/add-user', [GroupController::class, 'addUser'])->name('groups.add-user');
        Route::patch('groups/{group}/users/{user}/permission', [GroupController::class, 'updateUserPermission'])->name('groups.update-user-permission');
        Route::delete('groups/{group}/users/{user}', [GroupController::class, 'removeUser'])->name('groups.remove-user');
        
        // User management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/assign-groups', [UserController::class, 'assignGroups'])->name('users.assign-groups');
    });
});
