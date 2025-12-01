<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin SMK',
            'email' => 'admin@smk.sch.id',
            'phone_number' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'work_start_time' => '06:30:00',
            'work_end_time' => '15:00:00',
            'department' => 'Administrator',
        ]);
    }
}
