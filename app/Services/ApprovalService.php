<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceRevision;
use App\Models\Permit;
use Carbon\Carbon;

class ApprovalService
{
    /**
     * Approve permit request
     *
     * @param Permit $permit
     * @param string|null $adminNote
     * @return void
     */
    public function approvePermit(Permit $permit, ?string $adminNote = null): void
    {
        $permit->update([
            'status' => 'approved',
            'admin_note' => $adminNote,
        ]);

        // Create attendance records for approved permit dates
        $startDate = $permit->start_date;
        $endDate = $permit->end_date;

        while ($startDate->lte($endDate)) {
            Attendance::updateOrCreate(
                [
                    'user_id' => $permit->user_id,
                    'date' => $startDate->copy(),
                ],
                [
                    'clock_in' => $startDate->copy()->setTime(6, 30),
                    'clock_out' => $startDate->copy()->setTime(15, 0),
                    'lat_in' => 0,
                    'long_in' => 0,
                    'lat_out' => 0,
                    'long_out' => 0,
                    'status' => $permit->type === 'sick' ? 'sick' : 'permit',
                    'note' => 'Auto-created from approved permit: ' . $permit->reason,
                ]
            );

            $startDate->addDay();
        }
    }

    /**
     * Reject permit request
     *
     * @param Permit $permit
     * @param string|null $adminNote
     * @return void
     */
    public function rejectPermit(Permit $permit, ?string $adminNote = null): void
    {
        $permit->update([
            'status' => 'rejected',
            'admin_note' => $adminNote,
        ]);
    }

    /**
     * Approve attendance revision
     *
     * @param AttendanceRevision $revision
     * @return void
     */
    public function approveRevision(AttendanceRevision $revision): void
    {
        $revision->update(['status' => 'approved']);

        // Update or create attendance record
        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $revision->user_id,
                'date' => $revision->date,
            ],
            [
                'clock_in' => $revision->proposed_clock_in,
                'clock_out' => $revision->proposed_clock_out,
                'lat_in' => 0,
                'long_in' => 0,
                'lat_out' => 0,
                'long_out' => 0,
                'status' => 'present',
                'is_revision' => true,
                'note' => 'Revised: ' . $revision->reason,
            ]
        );

        // Link the revision to the attendance
        $revision->update(['attendance_id' => $attendance->id]);
    }

    /**
     * Reject attendance revision
     *
     * @param AttendanceRevision $revision
     * @return void
     */
    public function rejectRevision(AttendanceRevision $revision): void
    {
        $revision->update(['status' => 'rejected']);
    }

    /**
     * Get pending permits count
     *
     * @return int
     */
    public function getPendingPermitsCount(): int
    {
        return Permit::where('status', 'pending')->count();
    }

    /**
     * Get pending revisions count
     *
     * @return int
     */
    public function getPendingRevisionsCount(): int
    {
        return AttendanceRevision::where('status', 'pending')->count();
    }
}
