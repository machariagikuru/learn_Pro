<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoToLessonsTable extends Migration
{
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Adding a nullable string column "video" to store video information (e.g., URL or file path)
            $table->string('video')->nullable();
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('video');
        });
    }
}
