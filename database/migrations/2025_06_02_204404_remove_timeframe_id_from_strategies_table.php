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
        Schema::table('strategies', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['timeframe_id']);
            // Then drop the column
            $table->dropColumn('timeframe_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('strategies', function (Blueprint $table) {
            // Recreate the column and foreign key if rolling back
            $table->foreignId('timeframe_id')->nullable()->constrained()->onDelete('restrict');
        });
    }
};
