<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\AttendanceRevision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class RevisionRequest extends Component
{
    public $date;
    public $proposed_clock_in;
    public $proposed_clock_out;
    public $reason;
    public $message = '';
    public $status = '';
    public $revisions = [];

    protected $rules = [
        'date' => 'required|date|before_or_equal:today',
        'proposed_clock_in' => 'required',
        'proposed_clock_out' => 'required',
        'reason' => 'required|string|min:10',
    ];

    public function mount()
    {
        $this->loadRevisions();
    }

    public function loadRevisions()
    {
        $userId = Auth::id();
        $this->revisions = Cache::remember("user_revisions_{$userId}", 60, function () use ($userId) {
            return AttendanceRevision::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        });
    }

    public function submit()
    {
        $this->validate();

        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $this->date)
            ->first();

        AttendanceRevision::create([
            'user_id' => Auth::id(),
            'attendance_id' => $attendance?->id,
            'date' => $this->date,
            'proposed_clock_in' => $this->date . ' ' . $this->proposed_clock_in,
            'proposed_clock_out' => $this->date . ' ' . $this->proposed_clock_out,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        // Clear cache and reload
        Cache::forget("user_revisions_" . Auth::id());
        $this->loadRevisions();

        $this->status = 'success';
        $this->message = 'Pengajuan revisi absensi berhasil dikirim. Menunggu persetujuan admin.';
        $this->reset(['date', 'proposed_clock_in', 'proposed_clock_out', 'reason']);
    }

    public function render()
    {
        return view('livewire.revision-request');
    }
}
