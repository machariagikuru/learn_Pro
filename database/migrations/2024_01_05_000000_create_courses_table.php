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
        Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('image')->nullable();          // For storing the image path
        $table->string('short_video')->nullable();      // For storing the short video path
        $table->string('title');
        $table->text('description');
        $table->integer('duration');                    // Duration in minutes (adjust as needed)
        $table->decimal('price', 8, 2);                   // Price with 2 decimal places
        $table->unsignedBigInteger('user_id');          // Foreign key to users table
        $table->unsignedBigInteger('category_id');      // Foreign key to categories table
        $table->timestamps();

        // Define foreign key constraints
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
