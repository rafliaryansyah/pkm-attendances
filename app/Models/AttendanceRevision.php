<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRevision extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_id',
        'date',
        'proposed_clock_in',
        'proposed_clock_out',
        'reason',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'proposed_clock_in' => 'datetime',
            'proposed_clock_out' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
