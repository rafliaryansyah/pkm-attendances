<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            [
                'key' => 'attendance_location_lat',
                'value' => null,
                'type' => 'decimal',
                'description' => 'Latitude lokasi absensi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'attendance_location_long',
                'value' => null,
                'type' => 'decimal',
                'description' => 'Longitude lokasi absensi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'attendance_radius',
                'value' => '100',
                'type' => 'integer',
                'description' => 'Radius absensi dalam meter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'attendance_location_name',
                'value' => null,
                'type' => 'string',
                'description' => 'Nama lokasi absensi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
