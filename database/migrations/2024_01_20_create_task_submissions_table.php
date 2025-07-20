<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, rejected
            $table->integer('grade')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });

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

    public function down()
    {
        Schema::dropIfExists('submission_files');
        Schema::dropIfExists('task_submissions');
    }
}; 