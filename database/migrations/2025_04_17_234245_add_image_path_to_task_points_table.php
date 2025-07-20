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
        Schema::table('task_points', function (Blueprint $table) {
            // Add nullable string column to store the image path
            // Position it after code_block for logical grouping
            $table->string('image_path')->nullable()->after('code_block');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_points', function (Blueprint $table) {
            if (Schema::hasColumn('task_points', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};