<?php

namespace App\Livewire;

use App\Models\Permit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class PermitRequest extends Component
{
    use WithFileUploads;

    public $type = 'permit';
    public $start_date;
    public $end_date;
    public $reason;
    public $attachment;
    public $message = '';
    public $status = '';
    public $permits = [];

    protected $rules = [
        'type' => 'required|in:sick,permit',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|min:10',
        'attachment' => 'nullable|file|max:2048',
    ];

    public function mount()
    {
        $this->loadPermits();
    }

    public function loadPermits()
    {
        $userId = Auth::id();
        $this->permits = Cache::remember("user_permits_{$userId}", 60, function () use ($userId) {
            return Permit::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        });
    }

    public function submit()
    {
        $this->validate();

        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('permits', 'public');
        }

        Permit::create([
            'user_id' => Auth::id(),
            'type' => $this->type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        // Clear cache and reload
        Cache::forget("user_permits_" . Auth::id());
        $this->loadPermits();

        $this->status = 'success';
        $this->message = 'Pengajuan izin/sakit berhasil dikirim. Menunggu persetujuan admin.';
        $this->reset(['type', 'start_date', 'end_date', 'reason', 'attachment']);
    }

    public function render()
    {
        return view('livewire.permit-request');
    }
}
