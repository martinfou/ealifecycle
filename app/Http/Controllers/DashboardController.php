<?php

namespace App\Http\Controllers;

use App\Models\Strategy;
use App\Models\Status;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get strategy counts by status
        $strategyCounts = Strategy::where('user_id', $user->id)
            ->join('statuses', 'strategies.status_id', '=', 'statuses.id')
            ->selectRaw('statuses.name, statuses.color, COUNT(*) as count')
            ->groupBy('statuses.id', 'statuses.name', 'statuses.color')
            ->get();

        // Get recent strategies
        $recentStrategies = Strategy::where('user_id', $user->id)
            ->with(['status', 'timeframes'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Get total trades count
        $totalTrades = Trade::whereHas('strategy', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        // Get recent status changes
        $recentStatusChanges = \App\Models\StatusHistory::whereHas('strategy', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['strategy', 'previousStatus', 'newStatus', 'changedByUser'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return view('dashboard', compact(
            'strategyCounts', 
            'recentStrategies', 
            'totalTrades', 
            'recentStatusChanges'
        ));
    }
}
