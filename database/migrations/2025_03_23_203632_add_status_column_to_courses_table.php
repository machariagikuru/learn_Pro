<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // In a new migration file
public function up()
{
    Schema::table('courses', function (Blueprint $table) {
        $table->enum('status', ['pending', 'approved'])->default('pending')->after('why_choose_this_course');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
        });
    }
};
