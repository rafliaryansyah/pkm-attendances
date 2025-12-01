<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'lat_in',
        'long_in',
        'lat_out',
        'long_out',
        'status',
        'is_revision',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
            'lat_in' => 'decimal:8',
            'long_in' => 'decimal:8',
            'lat_out' => 'decimal:8',
            'long_out' => 'decimal:8',
            'is_revision' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(AttendanceRevision::class);
    }
}
