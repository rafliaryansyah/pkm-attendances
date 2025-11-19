<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    const LATE_TOLERANCE_MINUTES = 5;

    /**
     * Process check-in for a user
     *
     * @param User $user
     * @param float $lat
     * @param float $long
     * @param string|null $note
     * @return array
     */
    public static function checkIn(User $user, float $lat, float $long, ?string $note = null): array
    {
        // Validate geofencing
        if (!GeolocationService::isWithinAllowedRadius($lat, $long)) {
            $distance = GeolocationService::getDistanceFromSchool($lat, $long);
            return [
                'success' => false,
                'message' => "You are too far from school location. Distance: " . round($distance) . " meters (max: " . GeolocationService::ALLOWED_RADIUS . " meters)",
            ];
        }

        $today = Carbon::today();

        // Check if already checked in today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance) {
            return [
                'success' => false,
                'message' => 'You have already checked in today at ' . $existingAttendance->clock_in->format('H:i'),
            ];
        }

        $clockIn = Carbon::now();
        $status = self::determineStatus($user, $clockIn);

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'clock_in' => $clockIn,
            'lat_in' => $lat,
            'long_in' => $long,
            'status' => $status,
            'note' => $note,
        ]);

        return [
            'success' => true,
            'message' => 'Check-in successful' . ($status === 'late' ? ' (Late)' : ''),
            'attendance' => $attendance,
            'status' => $status,
        ];
    }

    /**
     * Process check-out for a user
     *
     * @param User $user
     * @param float $lat
     * @param float $long
     * @return array
     */
    public static function checkOut(User $user, float $lat, float $long): array
    {
        // Validate geofencing
        if (!GeolocationService::isWithinAllowedRadius($lat, $long)) {
            $distance = GeolocationService::getDistanceFromSchool($lat, $long);
            return [
                'success' => false,
                'message' => "You are too far from school location. Distance: " . round($distance) . " meters (max: " . GeolocationService::ALLOWED_RADIUS . " meters)",
            ];
        }

        $today = Carbon::today();

        // Find today's attendance
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return [
                'success' => false,
                'message' => 'You must check in first before checking out',
            ];
        }

        if ($attendance->clock_out) {
            return [
                'success' => false,
                'message' => 'You have already checked out today at ' . $attendance->clock_out->format('H:i'),
            ];
        }

        $attendance->update([
            'clock_out' => Carbon::now(),
            'lat_out' => $lat,
            'long_out' => $long,
        ]);

        return [
            'success' => true,
            'message' => 'Check-out successful',
            'attendance' => $attendance->fresh(),
        ];
    }

    /**
     * Determine attendance status based on check-in time
     *
     * @param User $user
     * @param Carbon $clockIn
     * @return string
     */
    protected static function determineStatus(User $user, Carbon $clockIn): string
    {
        $workStartTime = Carbon::parse($user->work_start_time);
        $lateThreshold = $workStartTime->copy()->addMinutes(self::LATE_TOLERANCE_MINUTES);

        if ($clockIn->format('H:i:s') > $lateThreshold->format('H:i:s')) {
            return 'late';
        }

        return 'present';
    }

    /**
     * Get today's attendance for a user
     *
     * @param User $user
     * @return Attendance|null
     */
    public static function getTodayAttendance(User $user): ?Attendance
    {
        return Attendance::where('user_id', $user->id)
            ->where('date', Carbon::today())
            ->first();
    }

    /**
     * Check if user has checked in today
     *
     * @param User $user
     * @return bool
     */
    public static function hasCheckedInToday(User $user): bool
    {
        return Attendance::where('user_id', $user->id)
            ->where('date', Carbon::today())
            ->exists();
    }

    /**
     * Check if user has checked out today
     *
     * @param User $user
     * @return bool
     */
    public static function hasCheckedOutToday(User $user): bool
    {
        $attendance = self::getTodayAttendance($user);
        return $attendance && $attendance->clock_out !== null;
    }
}
