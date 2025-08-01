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
        Schema::table('courses', function (Blueprint $table) {
            $table->text('why_choose_this_course')->nullable()->after('rate'); // Adjust "existing_column_name" if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('why_choose_this_course');
        });
    }
};
