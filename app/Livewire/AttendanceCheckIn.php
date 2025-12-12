<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Services\AttendanceService;
use App\Services\GeofencingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class AttendanceCheckIn extends Component
{
    public $latitude;
    public $longitude;
    public $status = '';
    public $message = '';
    public $todayAttendance;
    public $recentAttendances;
    public $targetLocation;
    public $banners;

    protected AttendanceService $attendanceService;
    protected GeofencingService $geofencingService;

    public function boot(AttendanceService $attendanceService, GeofencingService $geofencingService)
    {
        $this->attendanceService = $attendanceService;
        $this->geofencingService = $geofencingService;
    }

    public function mount()
    {
        $this->loadData();
        $this->targetLocation = Cache::remember('target_location', 300, function () {
            return $this->geofencingService->getTargetLocation();
        });
        $this->banners = Cache::remember('active_banners', 300, function () {
            return Banner::active()->ordered()->get();
        });
    }

    public function loadData()
    {
        $user = Auth::user();
        $this->todayAttendance = $this->attendanceService->getTodayAttendance($user);
        $this->recentAttendances = $this->attendanceService->getRecentAttendances($user, 5);
    }

    public function clockIn()
    {
        if (!$this->latitude || !$this->longitude) {
            $this->status = 'error';
            $this->message = 'Tidak dapat mengambil lokasi Anda. Pastikan GPS aktif.';
            return;
        }

        $result = $this->attendanceService->clockIn(
            Auth::user(),
            $this->latitude,
            $this->longitude
        );

        if ($result['success']) {
            $this->status = 'success';
            $this->message = $result['message'];
            $this->loadData();
        } else {
            $this->status = 'error';
            $this->message = $result['message'];
        }
    }

    public function clockOut()
    {
        if (!$this->latitude || !$this->longitude) {
            $this->status = 'error';
            $this->message = 'Tidak dapat mengambil lokasi Anda. Pastikan GPS aktif.';
            return;
        }

        $result = $this->attendanceService->clockOut(
            Auth::user(),
            $this->latitude,
            $this->longitude
        );

        if ($result['success']) {
            $this->status = 'success';
            $this->message = $result['message'];
            $this->loadData();
        } else {
            $this->status = 'error';
            $this->message = $result['message'];
        }
    }

    public function render()
    {
        return view('livewire.attendance-check-in');
    }
}
