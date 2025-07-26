<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insight_quest_submission_files', function (Blueprint $table) {
            // Rename original_name to file_name if it exists
            if (Schema::hasColumn('insight_quest_submission_files', 'original_name')) {
                $table->renameColumn('original_name', 'file_name');
            }
            // Add file_type column if it doesn't exist
            if (!Schema::hasColumn('insight_quest_submission_files', 'file_type')) {
                $table->string('file_type')->nullable();
            }
            // Add file_size column if it doesn't exist
            if (!Schema::hasColumn('insight_quest_submission_files', 'file_size')) {
                $table->integer('file_size')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('insight_quest_submission_files', function (Blueprint $table) {
            if (Schema::hasColumn('insight_quest_submission_files', 'file_name')) {
                $table->renameColumn('file_name', 'original_name');
            }
            if (Schema::hasColumn('insight_quest_submission_files', 'file_type')) {
                $table->dropColumn('file_type');
            }
            if (Schema::hasColumn('insight_quest_submission_files', 'file_size')) {
                $table->dropColumn('file_size');
            }
        });
    }
}; 