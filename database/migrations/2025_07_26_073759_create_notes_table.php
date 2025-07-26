<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('sub_strand_id')->nullable();
        $table->foreign('sub_strand_id')->references('id')->on('sub_strands')->onDelete('cascade');
        $table->string('title');
        $table->text('content');
        $table->string('grade')->nullable();
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};