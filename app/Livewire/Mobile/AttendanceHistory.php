<?php

namespace App\Livewire\Mobile;

use App\Models\Attendance;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('components.layouts.mobile')]
class AttendanceHistory extends Component
{
    use WithPagination;

    public $selectedMonth;
    public $selectedYear;

    protected $listeners = ['attendance-updated' => '$refresh'];

    public function mount()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
    }

    public function updatedSelectedMonth()
    {
        $this->resetPage();
    }

    public function updatedSelectedYear()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Attendance::with('user')
            ->where('user_id', auth()->id())
            ->whereYear('date', $this->selectedYear)
            ->whereMonth('date', $this->selectedMonth)
            ->orderBy('date', 'desc');

        $attendances = $query->paginate(31);

        // Statistics for selected month
        $stats = [
            'total_present' => Attendance::where('user_id', auth()->id())
                ->whereYear('date', $this->selectedYear)
                ->whereMonth('date', $this->selectedMonth)
                ->whereIn('status', ['present', 'late'])
                ->count(),
            'total_late' => Attendance::where('user_id', auth()->id())
                ->whereYear('date', $this->selectedYear)
                ->whereMonth('date', $this->selectedMonth)
                ->where('status', 'late')
                ->count(),
            'total_permit' => Attendance::where('user_id', auth()->id())
                ->whereYear('date', $this->selectedYear)
                ->whereMonth('date', $this->selectedMonth)
                ->whereIn('status', ['permit', 'sick'])
                ->count(),
        ];

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $years = range(now()->year, now()->year - 2);

        return view('livewire.mobile.attendance-history', [
            'attendances' => $attendances,
            'stats' => $stats,
            'months' => $months,
            'years' => $years,
        ]);
    }
}
