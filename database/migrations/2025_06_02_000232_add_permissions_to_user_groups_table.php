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
        Schema::table('user_groups', function (Blueprint $table) {
            $table->enum('permission', ['read', 'write'])->default('read')->after('group_id');
            $table->dropUnique(['user_id', 'group_id']);
            $table->unique(['user_id', 'group_id', 'permission']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'group_id', 'permission']);
            $table->unique(['user_id', 'group_id']);
            $table->dropColumn('permission');
        });
    }
};
