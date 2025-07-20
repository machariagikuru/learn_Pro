<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chapter_id');
            $table->string('title');
            $table->integer('time_limit')->nullable(); // e.g. in minutes
            $table->integer('passing_score')->nullable(); // e.g. 75 means 75%
            $table->timestamps();

            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
};
