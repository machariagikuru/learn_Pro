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
        Schema::create('user_courses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->unsignedBigInteger('course_id'); // Foreign key to courses table
            $table->date('enrollment_date')->default(now()); // Enrollment date (defaults to current date)
            $table->string('status')->default('active'); // Enrollment status (e.g., active, cancelled)
            $table->timestamps(); // created_at and updated_at columns

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_courses');
    }
};
