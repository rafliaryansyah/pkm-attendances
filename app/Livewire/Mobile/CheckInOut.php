<?php

namespace App\Livewire\Mobile;

use App\Services\AttendanceService;
use App\Services\GeolocationService;
use App\Models\Permit;
use Livewire\Component;
use Carbon\Carbon;

class CheckInOut extends Component
{
    public $latitude;
    public $longitude;
    public $note;
    public $loading = false;
    public $message;
    public $messageType;

    public function checkIn()
    {
        $this->loading = true;
        $this->resetMessage();

        $this->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $result = AttendanceService::checkIn(
            auth()->user(),
            $this->latitude,
            $this->longitude,
            $this->note
        );

        $this->messageType = $result['success'] ? 'success' : 'error';
        $this->message = $result['message'];
        $this->loading = false;

        if ($result['success']) {
            $this->note = '';
            $this->dispatch('attendance-updated');
        }
    }

    public function checkOut()
    {
        $this->loading = true;
        $this->resetMessage();

        $this->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $result = AttendanceService::checkOut(
            auth()->user(),
            $this->latitude,
            $this->longitude
        );

        $this->messageType = $result['success'] ? 'success' : 'error';
        $this->message = $result['message'];
        $this->loading = false;

        if ($result['success']) {
            $this->dispatch('attendance-updated');
        }
    }

    public function resetMessage()
    {
        $this->message = null;
        $this->messageType = null;
    }

    public function render()
    {
        $todayAttendance = AttendanceService::getTodayAttendance(auth()->user());
        $hasCheckedIn = AttendanceService::hasCheckedInToday(auth()->user());
        $hasCheckedOut = AttendanceService::hasCheckedOutToday(auth()->user());

        // Get today's leave/permit users
        $todayLeave = Permit::with('user')
            ->where('status', 'approved')
            ->where('start_date', '<=', Carbon::today())
            ->where('end_date', '>=', Carbon::today())
            ->get();

        $distance = null;
        if ($this->latitude && $this->longitude) {
            $distance = GeolocationService::getDistanceFromSchool(
                $this->latitude,
                $this->longitude
            );
        }

        return view('livewire.mobile.check-in-out', [
            'todayAttendance' => $todayAttendance,
            'hasCheckedIn' => $hasCheckedIn,
            'hasCheckedOut' => $hasCheckedOut,
            'todayLeave' => $todayLeave,
            'distance' => $distance,
        ]);
    }
}
