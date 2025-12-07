<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // generate 20 dummy users
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => 'Dummy User ' . $i,
                'email' => 'user+' . $i . '@gmail.com',
                'phone_number' => '08123' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'work_start_time' => '06:30:00',
                'work_end_time' => '15:00:00',
                'department' => 'General',
            ]);
        }
    }
}
