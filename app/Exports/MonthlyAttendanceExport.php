<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Permit;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class MonthlyAttendanceExport implements WithMultipleSheets
{
    protected ?string $filterType;
    protected ?string $month;
    protected ?string $year;
    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct(
        ?string $filterType = 'monthly',
        ?string $month = null,
        ?string $year = null,
        ?string $startDate = null,
        ?string $endDate = null
    ) {
        $this->filterType = $filterType;
        $this->month = $month;
        $this->year = $year;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            new MonthlySummarySheet($this->filterType, $this->month, $this->year, $this->startDate, $this->endDate),
        ];
    }
}

class MonthlySummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected ?string $filterType;
    protected ?string $month;
    protected ?string $year;
    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct(
        ?string $filterType,
        ?string $month,
        ?string $year,
        ?string $startDate,
        ?string $endDate
    ) {
        $this->filterType = $filterType;
        $this->month = $month;
        $this->year = $year;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): Collection
    {
        $users = User::orderBy('name', 'asc')->get();
        $summary = collect();
        $index = 0;

        $dateRange = $this->getDateRange();

        foreach ($users as $user) {
            $index++;

            $attendanceQuery = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$dateRange['start'], $dateRange['end']]);

            $presentDays = (clone $attendanceQuery)->where('status', 'present')->count();
            $lateDays = (clone $attendanceQuery)->where('status', 'late')->count();
            $absentDays = (clone $attendanceQuery)->where('status', 'absent')->count();

            $onLeaveDays = Permit::where('user_id', $user->id)
                ->where('status', 'approved')
                ->where(function ($query) use ($dateRange) {
                    $query->whereBetween('start_date', [$dateRange['start'], $dateRange['end']])
                        ->orWhereBetween('end_date', [$dateRange['start'], $dateRange['end']])
                        ->orWhere(function ($q) use ($dateRange) {
                            $q->where('start_date', '<=', $dateRange['start'])
                              ->where('end_date', '>=', $dateRange['end']);
                        });
                })
                ->get()
                ->sum(function ($permit) use ($dateRange) {
                    $start = Carbon::parse(max($permit->start_date, $dateRange['start']));
                    $end = Carbon::parse(min($permit->end_date, $dateRange['end']));
                    return $start->diffInDays($end) + 1;
                });

            $totalDays = $presentDays + $lateDays + $absentDays + $onLeaveDays;
            $attendanceRate = $totalDays > 0 
                ? round((($presentDays + $lateDays) / $totalDays) * 100, 1) . '%' 
                : '0%';

            $roleMap = [
                'admin' => 'Admin',
                'teacher' => 'Teacher',
                'staff' => 'Staff',
            ];

            $summary->push([
                'no' => $index,
                'name' => $user->name,
                'role' => $roleMap[$user->role] ?? ucfirst($user->role),
                'department' => $user->department ?? '-',
                'total_days' => $totalDays,
                'present' => $presentDays,
                'late' => $lateDays,
                'absent' => $absentDays,
                'on_leave' => $onLeaveDays,
                'attendance_rate' => $attendanceRate,
            ]);
        }

        return $summary;
    }

    protected function getDateRange(): array
    {
        if ($this->filterType === 'monthly') {
            $startDate = Carbon::createFromFormat('Y-m', $this->year . '-' . $this->month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $this->year . '-' . $this->month)->endOfMonth();
        } else {
            $startDate = Carbon::parse($this->startDate);
            $endDate = Carbon::parse($this->endDate);
        }

        return [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Role',
            'Departemen',
            'Total Hari',
            'Hadir',
            'Terlambat',
            'Tidak Hadir',
            'Izin/Cuti/Sakit',
            '% Kehadiran',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        $monthNames = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];
        
        if ($this->filterType === 'monthly') {
            return 'Rekap ' . ($monthNames[$this->month] ?? '') . ' ' . $this->year;
        } else {
            $start = Carbon::parse($this->startDate)->format('d-m-Y');
            $end = Carbon::parse($this->endDate)->format('d-m-Y');
            return 'Rekap ' . $start . ' s.d ' . $end;
        }
    }
}
