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
        Schema::create('insight_quest_submission_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insight_quest_submission_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->timestamps();

            $table->foreign('insight_quest_submission_id', 'submission_file_submission_id_foreign')
                ->references('id')->on('insight_quest_submissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insight_quest_submission_files');
    }
};
