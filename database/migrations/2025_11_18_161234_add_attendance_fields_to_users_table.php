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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->unique()->nullable()->after('email');
            $table->enum('role', ['admin', 'teacher', 'staff'])->default('teacher')->after('password');
            $table->time('work_start_time')->default('06:30:00')->after('role');
            $table->time('work_end_time')->default('15:00:00')->after('work_start_time');
            $table->string('department')->nullable()->after('work_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'role', 'work_start_time', 'work_end_time', 'department']);
        });
    }
};
