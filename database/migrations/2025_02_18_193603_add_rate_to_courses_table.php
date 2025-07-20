<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Adds a column 'rate' that accepts integers from 1 to 5.
            // Default is set to 1 (you can adjust this as needed).
            $table->unsignedTinyInteger('rate')->default(1)->comment('Course rating from 1 to 5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('rate');
        });
    }
}
