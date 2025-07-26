<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLongTitleToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('long_title')->nullable()->after('title')
                  ->comment('The extended or detailed title of the course');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('long_title');
        });
    }
}