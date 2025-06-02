<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing timeframe_id data to the pivot table
        $strategies = DB::table('strategies')->whereNotNull('timeframe_id')->get();
        
        foreach ($strategies as $strategy) {
            DB::table('strategy_timeframes')->insert([
                'strategy_id' => $strategy->id,
                'timeframe_id' => $strategy->timeframe_id,
                'is_primary' => true, // Mark the existing timeframe as primary
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all data from pivot table (this will be recreated if we rollback)
        DB::table('strategy_timeframes')->truncate();
    }
};
