<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, create the submission_files table if it doesn't exist
        if (!Schema::hasTable('submission_files')) {
            Schema::create('submission_files', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_submission_id')->constrained()->onDelete('cascade');
                $table->string('file_name');
                $table->string('file_path');
                $table->string('file_type');
                $table->integer('file_size');
                $table->timestamps();
            });
        }

        // Then, update the task_submissions table
        Schema::table('task_submissions', function (Blueprint $table) {
            // Drop existing columns if they exist
            $table->dropColumn(['file_path', 'score', 'instructor_feedback']);
            
            // Add new columns if they don't exist
            if (!Schema::hasColumn('task_submissions', 'grade')) {
                $table->integer('grade')->nullable();
            }
            if (!Schema::hasColumn('task_submissions', 'feedback')) {
                $table->text('feedback')->nullable();
            }
        });
    }

    public function down()
    {
        // Drop the new table
        Schema::dropIfExists('submission_files');

        // Revert changes to task_submissions
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->string('file_path')->nullable();
            $table->integer('score')->nullable();
            $table->text('instructor_feedback')->nullable();
            
            $table->dropColumn(['grade', 'feedback']);
        });
    }
}; 