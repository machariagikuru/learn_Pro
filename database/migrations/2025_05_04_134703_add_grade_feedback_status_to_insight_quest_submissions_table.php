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
        Schema::table('insight_quest_submissions', function (Blueprint $table) {
            $table->integer('grade')->nullable();
            $table->text('feedback')->nullable();
            $table->string('status')->default('pending');
            $table->json('files')->nullable();
            $table->dropColumn(['file_path', 'original_name']);
        });
    }
    public function down()
    {
        Schema::table('insight_quest_submissions', function (Blueprint $table) {
            $table->dropColumn(['grade', 'feedback', 'status', 'files']);
            $table->string('file_path')->nullable();
            $table->string('original_name')->nullable();
        });
    }
};
