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
        // Check if the column exists before trying to drop it
        if (Schema::hasColumn('tasks', 'task_points')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('task_points');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add the column back if needed for rollback
            // Adjust the type if it wasn't 'text' originally
            $table->text('task_points')->nullable();
        });
    }
};