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
        Schema::create('portfolio_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('strategy_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('action_type', [
                'created',
                'updated', 
                'status_changed',
                'strategy_added',
                'strategy_updated',
                'strategy_activated',
                'strategy_paused',
                'strategy_removed'
            ]);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['portfolio_id', 'created_at']);
            $table->index(['strategy_id', 'created_at']);
            $table->index(['action_type', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_histories');
    }
};
