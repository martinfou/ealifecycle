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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategy_id')->nullable()->constrained()->onDelete('set null'); // Can be manually associated
            $table->bigInteger('magic_number')->nullable(); // From FX Blue data
            $table->string('symbol'); // Trading symbol
            $table->enum('type', ['buy', 'sell']); // Trade type
            $table->decimal('lot_size', 10, 2); // Trade size
            $table->decimal('open_price', 15, 5); // Entry price
            $table->decimal('close_price', 15, 5)->nullable(); // Exit price
            $table->timestamp('open_time'); // Trade open time
            $table->timestamp('close_time')->nullable(); // Trade close time
            $table->decimal('profit', 15, 2)->nullable(); // Profit/Loss
            $table->decimal('commission', 15, 2)->nullable(); // Commission
            $table->decimal('swap', 15, 2)->nullable(); // Swap
            $table->string('comment')->nullable(); // Trade comment
            $table->string('original_file')->nullable(); // Source file name
            $table->json('raw_data')->nullable(); // Store original row data as JSON
            $table->foreignId('imported_by_user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            $table->index(['strategy_id', 'open_time']);
            $table->index(['magic_number', 'open_time']);
            $table->index('symbol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
