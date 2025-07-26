<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new columns
            $table->string('first_name')->after('id'); // After the primary key
            $table->string('last_name')->after('first_name');
            $table->string('email')->nullable()->change(); // Make email nullable
            $table->string('phone')->nullable()->change(); // Make phone nullable
            $table->date('dob')->nullable()->after('phone'); // Add date of birth
            $table->unique(['email', 'phone']); // Ensure uniqueness for either

            // Drop unnecessary columns
            $table->dropColumn(['name', 'address']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes
            $table->string('name')->after('id');
            $table->string('address')->nullable()->after('phone');
            $table->dropColumn(['first_name', 'last_name', 'dob']);
            $table->dropUnique(['email', 'phone']);
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
        });
    }
};
