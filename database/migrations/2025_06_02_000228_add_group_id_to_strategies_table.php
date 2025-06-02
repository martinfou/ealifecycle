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
            $table->foreignId('group_id')->nullable()->after('user_id')->constrained()->onDelete('restrict');
            $table->index(['group_id', 'status_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('strategies', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropIndex(['group_id', 'status_id']);
            $table->dropColumn('group_id');
        });
    }
};
