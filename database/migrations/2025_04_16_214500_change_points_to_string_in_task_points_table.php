<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Change the 'points' column from integer to string.
     */
    public function up(): void
    {
        Schema::table('task_points', function (Blueprint $table) {
            // Change the 'points' column to a string type
            // You can adjust the length (e.g., 255) if needed
            $table->string('points')->change();
        });
    }

    /**
     * Reverse the migrations.
     * Change the 'points' column back to integer (if possible).
     * Note: Reversing might cause data loss if strings cannot be cast to integers.
     */
    public function down(): void
    {
        Schema::table('task_points', function (Blueprint $table) {
            // Attempt to change back to integer. Add ->nullable() if it was nullable before.
            // Be cautious with rollback if data integrity is critical.
             // $table->integer('points')->change();
             // Safer approach: Add a new integer column, migrate data, drop string, rename new column.
             // For simplicity here, we'll just attempt the change. Consider implications.
             $table->integer('points')->default(0)->change(); // Change back, provide default
        });
    }
};