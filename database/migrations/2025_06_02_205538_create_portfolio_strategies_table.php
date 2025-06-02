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
        Schema::create('portfolio_strategies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('strategy_id')->constrained()->onDelete('cascade');
            $table->decimal('allocation_amount', 15, 2)->default(0);
            $table->decimal('allocation_percent', 5, 2)->default(0); // 0.00 to 999.99%
            $table->enum('status', ['active', 'paused', 'removed'])->default('active');
            $table->date('date_added');
            $table->date('date_removed')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Ensure unique portfolio-strategy combination (unless removed)
            $table->unique(['portfolio_id', 'strategy_id'], 'portfolio_strategy_unique');
            
            // Indexes for performance
            $table->index(['portfolio_id', 'status']);
            $table->index('strategy_id');
            $table->index('date_added');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_strategies');
    }
};
