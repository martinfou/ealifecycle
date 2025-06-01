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
        Schema::create('status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategy_id')->constrained()->onDelete('cascade');
            $table->foreignId('previous_status_id')->nullable()->constrained('statuses')->onDelete('restrict');
            $table->foreignId('new_status_id')->constrained('statuses')->onDelete('restrict');
            $table->foreignId('changed_by_user_id')->constrained('users')->onDelete('restrict');
            $table->text('notes')->nullable(); // Optional notes about the status change
            $table->timestamps();
            
            $table->index(['strategy_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_history');
    }
};
