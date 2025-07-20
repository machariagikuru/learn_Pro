<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lesson_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lesson_id');
            $table->boolean('watched')->default(false);
            $table->timestamps();

            // Use a composite primary key so that a user can only have one entry per lesson.
            $table->primary(['user_id', 'lesson_id']);
            
            // Foreign key constraints (if needed)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_user');
    }
};
