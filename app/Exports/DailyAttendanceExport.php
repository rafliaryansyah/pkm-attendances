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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyAttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected ?string $date;

    public function __construct(?string $date = null)
    {
        $this->date = $date ?: now()->format('Y-m-d');
    }

    public function collection()
    {
        $users = User::orderBy('name', 'asc')->get();
        $date = $this->date;
        
        return $users->map(function ($user) use ($date) {
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->first();
            
            $permit = Permit::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->first();
            
            $status = 'Tidak Hadir';
            $clockIn = '-';
            $clockOut = '-';
            $note = '-';
            
            if ($attendance) {
                $statusMap = [
                    'Hadir' => 'Hadir',
                    'Telat' => 'Terlambat',
                    'Alpha' => 'Tidak Hadir',
                    'Izin' => 'Izin',
                    'Sakit' => 'Sakit',
                ];
                $status = $statusMap[$attendance->status] ?? $attendance->status;
                $clockIn = $attendance->clock_in ? Carbon::parse($attendance->clock_in)->format('H:i:s') : '-';
                $clockOut = $attendance->clock_out ? Carbon::parse($attendance->clock_out)->format('H:i:s') : '-';
                $note = $attendance->note ?? '-';
            } elseif ($permit) {
                $typeMap = [
                    'sick' => 'Sakit',
                    'leave' => 'Izin',
                    'vacation' => 'Cuti',
                ];
                $status = $typeMap[$permit->type] ?? $permit->type;
                $note = $permit->reason ?? '-';
            }
            
            return (object) [
                'user' => $user,
                'status' => $status,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'note' => $note,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Role',
            'Departemen',
            'Status Kehadiran',
            'Check In',
            'Check Out',
            'Keterangan',
        ];
    }

    public function map($record): array
    {
        static $index = 0;
        $index++;

        $roleMap = [
            'admin' => 'Admin',
            'teacher' => 'Teacher',
            'staff' => 'Staff',
        ];

        return [
            $index,
            $record->user->name ?? '-',
            $roleMap[$record->user->role] ?? ucfirst($record->user->role),
            $record->user->department ?? '-',
            $record->status,
            $record->clock_in,
            $record->clock_out,
            $record->note,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Harian ' . Carbon::parse($this->date)->format('d-m-Y');
    }
}
