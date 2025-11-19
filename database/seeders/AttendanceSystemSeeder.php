<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Permit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AttendanceSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin SMK',
            'email' => 'admin@smk.sch.id',
            'phone_number' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'work_start_time' => '07:00:00',
            'work_end_time' => '16:00:00',
            'department' => 'Administration',
        ]);

        // Create Teachers
        $teachers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@smk.sch.id',
                'phone_number' => '081234567891',
                'department' => 'Guru TKJ',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@smk.sch.id',
                'phone_number' => '081234567892',
                'department' => 'Guru Multimedia',
            ],
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad@smk.sch.id',
                'phone_number' => '081234567893',
                'department' => 'Guru RPL',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@smk.sch.id',
                'phone_number' => '081234567894',
                'department' => 'Guru Matematika',
            ],
        ];

        foreach ($teachers as $teacher) {
            User::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'phone_number' => $teacher['phone_number'],
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'work_start_time' => '06:30:00',
                'work_end_time' => '15:00:00',
                'department' => $teacher['department'],
            ]);
        }

        // Create Staff
        $staffs = [
            [
                'name' => 'Rina Wati',
                'email' => 'rina@smk.sch.id',
                'phone_number' => '081234567895',
                'department' => 'Tata Usaha',
            ],
            [
                'name' => 'Joko Susilo',
                'email' => 'joko@smk.sch.id',
                'phone_number' => '081234567896',
                'department' => 'IT Support',
            ],
        ];

        foreach ($staffs as $staff) {
            User::create([
                'name' => $staff['name'],
                'email' => $staff['email'],
                'phone_number' => $staff['phone_number'],
                'password' => Hash::make('password'),
                'role' => 'staff',
                'work_start_time' => '07:00:00',
                'work_end_time' => '16:00:00',
                'department' => $staff['department'],
            ]);
        }

        // Create sample attendance records for the last 7 days
        $users = User::where('role', '!=', 'admin')->get();
        $today = Carbon::today();

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);

            foreach ($users as $user) {
                // Random attendance pattern (90% present, 5% late, 5% absent)
                $rand = rand(1, 100);

                if ($rand <= 90) {
                    // Present
                    $clockInTime = Carbon::parse($user->work_start_time)
                        ->setDate($date->year, $date->month, $date->day)
                        ->addMinutes(rand(-10, 2)); // Some arrive early, some on time

                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $date,
                        'clock_in' => $clockInTime,
                        'clock_out' => $clockInTime->copy()->addHours(8)->addMinutes(rand(-15, 30)),
                        'lat_in' => -6.154928,
                        'long_in' => 106.772240,
                        'lat_out' => -6.154928,
                        'long_out' => 106.772240,
                        'status' => 'present',
                    ]);
                } elseif ($rand <= 95) {
                    // Late
                    $clockInTime = Carbon::parse($user->work_start_time)
                        ->setDate($date->year, $date->month, $date->day)
                        ->addMinutes(rand(10, 30));

                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $date,
                        'clock_in' => $clockInTime,
                        'clock_out' => $clockInTime->copy()->addHours(8)->addMinutes(rand(-15, 30)),
                        'lat_in' => -6.154928,
                        'long_in' => 106.772240,
                        'lat_out' => -6.154928,
                        'long_out' => 106.772240,
                        'status' => 'late',
                    ]);
                }
                // else: no record (absent/alpha)
            }
        }

        // Create sample permit
        Permit::create([
            'user_id' => $users->random()->id,
            'type' => 'sick',
            'start_date' => $today->copy()->addDays(1),
            'end_date' => $today->copy()->addDays(2),
            'reason' => 'Flu and fever',
            'status' => 'pending',
        ]);

        $this->command->info('Sample data created successfully!');
        $this->command->info('');
        $this->command->info('Admin Credentials:');
        $this->command->info('Email: admin@smk.sch.id');
        $this->command->info('Password: password');
        $this->command->info('');
        $this->command->info('Teacher/Staff Credentials:');
        $this->command->info('Email: budi@smk.sch.id (or other emails)');
        $this->command->info('Password: password');
    }
}
