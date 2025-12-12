<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    private GeofencingService $geofencingService;

    public function __construct(GeofencingService $geofencingService)
    {
        $this->geofencingService = $geofencingService;
    }

    /**
     * Process clock in for user
     *
     * @param User $user
     * @param float $latitude
     * @param float $longitude
     * @return array
     */
    public function clockIn(User $user, float $latitude, float $longitude): array
    {
        // Validate geofence (disabled temporarily)
        // if (!$this->geofencingService->isWithinGeofence($latitude, $longitude)) {
        //     $distance = $this->geofencingService->getDistanceFromTarget($latitude, $longitude);
        //     return [
        //         'success' => false,
        //         'message' => "Anda berada di luar area absensi. Jarak: " . round($distance) . " meter.",
        //     ];
        // }

        // Check if already clocked in today
        $today = Carbon::today();
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan clock in hari ini.',
            ];
        }

        // Determine status (present or late)
        $clockInTime = Carbon::now();
        $workStartTime = Carbon::parse($user->work_start_time);
        $toleranceMinutes = 5;
        $lateThreshold = $workStartTime->addMinutes($toleranceMinutes);

        $status = $clockInTime->greaterThan($lateThreshold) ? 'late' : 'present';

        // Create attendance record
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'clock_in' => $clockInTime,
            'lat_in' => $latitude,
            'long_in' => $longitude,
            'status' => $status,
        ]);

        return [
            'success' => true,
            'message' => 'Clock in berhasil!',
            'attendance' => $attendance,
            'status' => $status,
        ];
    }

    /**
     * Process clock out for user
     *
     * @param User $user
     * @param float $latitude
     * @param float $longitude
     * @return array
     */
    public function clockOut(User $user, float $latitude, float $longitude): array
    {
        // Validate geofence (disabled temporarily)
        // if (!$this->geofencingService->isWithinGeofence($latitude, $longitude)) {
        //     $distance = $this->geofencingService->getDistanceFromTarget($latitude, $longitude);
        //     return [
        //         'success' => false,
        //         'message' => "Anda berada di luar area absensi. Jarak: " . round($distance) . " meter.",
        //     ];
        // }

        // Find today's attendance
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return [
                'success' => false,
                'message' => 'Anda belum melakukan clock in hari ini.',
            ];
        }

        if ($attendance->clock_out) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan clock out hari ini.',
            ];
        }

        // Update attendance with clock out
        $attendance->update([
            'clock_out' => Carbon::now(),
            'lat_out' => $latitude,
            'long_out' => $longitude,
        ]);

        return [
            'success' => true,
            'message' => 'Clock out berhasil!',
            'attendance' => $attendance,
        ];
    }

    /**
     * Get today's attendance for user
     *
     * @param User $user
     * @return Attendance|null
     */
    public function getTodayAttendance(User $user): ?Attendance
    {
        return Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();
    }

    /**
     * Get recent attendances for user
     *
     * @param User $user
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentAttendances(User $user, int $days = 5)
    {
        return Attendance::where('user_id', $user->id)
            ->whereDate('date', '>=', Carbon::today()->subDays($days))
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get dashboard statistics for today
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        $today = Carbon::today();

        return [
            'present' => Attendance::whereDate('date', $today)
                ->where('status', 'present')
                ->count(),
            'late' => Attendance::whereDate('date', $today)
                ->where('status', 'late')
                ->count(),
            'permit' => Attendance::whereDate('date', $today)
                ->where('status', 'permit')
                ->count(),
            'sick' => Attendance::whereDate('date', $today)
                ->where('status', 'sick')
                ->count(),
            'alpha' => Attendance::whereDate('date', $today)
                ->where('status', 'alpha')
                ->count(),
        ];
    }
}
