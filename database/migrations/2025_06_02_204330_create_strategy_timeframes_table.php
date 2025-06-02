<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('strategy_timeframes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategy_id')->constrained()->onDelete('cascade');
            $table->foreignId('timeframe_id')->constrained()->onDelete('cascade');
            $table->boolean('is_primary')->default(false); // Mark one timeframe as primary for display
            $table->timestamps();
            
            // Ensure unique combination of strategy and timeframe
            $table->unique(['strategy_id', 'timeframe_id']);
            
            // Add indexes for performance
            $table->index('strategy_id');
            $table->index('timeframe_id');
            $table->index(['strategy_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategy_timeframes');
    }
};
