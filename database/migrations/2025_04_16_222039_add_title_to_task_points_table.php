<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add a 'title' column to the task_points table.
     */
    public function up(): void
    {
        Schema::table('task_points', function (Blueprint $table) {
            // Add a nullable string column for the title after the task_id column
            $table->string('title')->nullable()->after('task_id');
        });
    }

    /**
     * Reverse the migrations.
     * Remove the 'title' column.
     */
    public function down(): void
    {
        Schema::table('task_points', function (Blueprint $table) {
            // Add safety check in case the column doesn't exist during rollback
            if (Schema::hasColumn('task_points', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};