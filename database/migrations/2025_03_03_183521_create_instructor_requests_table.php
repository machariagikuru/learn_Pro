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
        Schema::create('instructor_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // رقم المستخدم
            $table->string('status')->default('pending'); // الحالة: pending, approved, rejected
            $table->text('message')->nullable(); // رسالة الطلب (اختياري)
            $table->timestamps();
            
            // ربط المفتاح الخارجي مع جدول المستخدمين
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_requests');
    }
};
