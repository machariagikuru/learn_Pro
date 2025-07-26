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
        // Ensure doctrine/dbal is installed for 'change()' to work
        // composer require doctrine/dbal

        Schema::table('task_points', function (Blueprint $table) {
            // Ensure title exists and is non-nullable
            if (!Schema::hasColumn('task_points', 'title')) {
                $table->string('title')->after('task_id');
            }
            // Make title non-nullable using change()
            $table->string('title')->nullable(false)->change();

            // Add 'notes' if it doesn't exist
            if (!Schema::hasColumn('task_points', 'notes')) {
                $table->text('notes')->nullable()->after('title');
            } else {
                // Ensure it's positioned correctly if it already exists
                $table->text('notes')->nullable()->after('title')->change();
            }

            // Add 'code_block' if it doesn't exist
            if (!Schema::hasColumn('task_points', 'code_block')) {
                 $table->text('code_block')->nullable()->after('notes');
            } else {
                 // Ensure it's positioned correctly if it already exists
                 $table->text('code_block')->nullable()->after('notes')->change();
            }

            // Ensure 'points' column exists, is JSON, nullable, and positioned correctly
            if (Schema::hasColumn('task_points', 'points')) {
                 // Use change() to modify type, nullability, and position
                 $table->json('points')->nullable()->after('code_block')->change();
            } else {
                 // Create the column if it doesn't exist
                 $table->json('points')->nullable()->after('code_block');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // Ensure doctrine/dbal is installed for 'change()' to work

        Schema::table('task_points', function (Blueprint $table) {
            // Revert 'points' back to string - DATA LOSS POSSIBLE
            if (Schema::hasColumn('task_points', 'points')) {
                 // Assuming original was VARCHAR(255) NOT NULL
                 // Check if column type *is* json before attempting revert might still be wise,
                 // but requires doctrine/dbal and can fail as seen before.
                 // Direct change is simpler but relies on knowing the previous state.
                 try {
                     $table->string('points', 255)->nullable(false)->change();
                 } catch (\Exception $e) {
                     // Log error or handle if changing back fails (e.g., if data is not string-compatible)
                     Log::warning("Could not revert 'points' column type in migration rollback: " . $e->getMessage());
                 }
            }

            // Drop added columns
            if (Schema::hasColumn('task_points', 'code_block')) $table->dropColumn('code_block');
            if (Schema::hasColumn('task_points', 'notes')) $table->dropColumn('notes');

            // Revert title back to nullable string
            if (Schema::hasColumn('task_points', 'title')) {
                try {
                   $table->string('title')->nullable()->change();
                } catch (\Exception $e) {
                     Log::warning("Could not revert 'title' column type in migration rollback: " . $e->getMessage());
                }
            }
        });
    }
};