<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id(); // المفتاح الأساسي
            $table->unsignedBigInteger('user_id');   // معرف المستخدم
            $table->unsignedBigInteger('lesson_id');   // معرف الدرس
            $table->boolean('watched')->default(false); // تحديد ما إذا تم مشاهدة الدرس
            $table->decimal('time_spent', 8, 2)->default(0); // الوقت الذي قُضي في مشاهدة الدرس (يمكن أن يكون بالدقائق أو بالساعات)
            $table->timestamps(); // created_at و updated_at

            // قيود المفتاح الخارجي
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
}
