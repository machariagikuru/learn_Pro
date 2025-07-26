<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('chapter_id');
            $table->string('title'); // task title
            $table->text('description')->nullable();
            $table->string('videos_required_watched'); // store number or string
            $table->timestamps();

            // Foreign keys (make sure courses and chapters exist)
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
