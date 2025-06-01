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
        Schema::create('strategies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('symbols_traded')->nullable(); // Free-text input for symbols
            $table->foreignId('timeframe_id')->constrained()->onDelete('restrict');
            $table->bigInteger('magic_number')->nullable()->unique(); // Optional, unique magic number
            $table->foreignId('status_id')->constrained()->onDelete('restrict');
            $table->date('date_in_status'); // Date when strategy entered current status
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Strategy owner
            $table->text('description')->nullable(); // Additional description
            $table->timestamps();
            
            $table->index(['user_id', 'status_id']);
            $table->index('magic_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategies');
    }
};
