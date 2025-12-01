<?php

namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportingService
{
    /**
     * Get attendance report with custom date range
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return Collection
     */
    public function getAttendanceReport(Carbon $startDate, Carbon $endDate): Collection
    {
        return Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->orderBy('user_id')
            ->get();
    }

    /**
     * Get monthly report (from 24th prev month to 23rd current month)
     *
     * @param int $month
     * @param int $year
     * @return Collection
     */
    public function getMonthlyReport(int $month, int $year): Collection
    {
        // Start from 24th of previous month
        $startDate = Carbon::create($year, $month, 1)->subMonth()->day(24);
        // End on 23rd of current month
        $endDate = Carbon::create($year, $month, 23);

        return $this->getAttendanceReport($startDate, $endDate);
    }

    /**
     * Get attendance statistics for date range
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getStatistics(Carbon $startDate, Carbon $endDate): array
    {
        $attendances = $this->getAttendanceReport($startDate, $endDate);

        return [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'permit' => $attendances->where('status', 'permit')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'alpha' => $attendances->where('status', 'alpha')->count(),
        ];
    }

    /**
     * Get user attendance summary
     *
     * @param int $userId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getUserAttendanceSummary(int $userId, Carbon $startDate, Carbon $endDate): array
    {
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $totalDays = $startDate->diffInDays($endDate) + 1;
        $totalAttendance = $attendances->count();

        return [
            'user_id' => $userId,
            'total_days' => $totalDays,
            'total_attendance' => $totalAttendance,
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'permit' => $attendances->where('status', 'permit')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'alpha' => $totalDays - $totalAttendance,
            'attendance_rate' => $totalDays > 0 ? round(($totalAttendance / $totalDays) * 100, 2) : 0,
        ];
    }

    /**
     * Export attendance data to array format (for Excel/PDF)
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function exportData(Carbon $startDate, Carbon $endDate): array
    {
        $attendances = $this->getAttendanceReport($startDate, $endDate);

        return $attendances->map(function ($attendance) {
            return [
                'Tanggal' => $attendance->date->format('d-m-Y'),
                'Nama' => $attendance->user->name,
                'Departemen' => $attendance->user->department,
                'Check In' => $attendance->clock_in->format('H:i:s'),
                'Check Out' => $attendance->clock_out ? $attendance->clock_out->format('H:i:s') : '-',
                'Status' => ucfirst($attendance->status),
                'Revisi' => $attendance->is_revision ? 'Ya' : 'Tidak',
                'Catatan' => $attendance->note ?? '-',
            ];
        })->toArray();
    }
}
