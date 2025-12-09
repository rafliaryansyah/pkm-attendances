<?php

namespace App\Filament\Pages;

use App\Exports\MonthlyAttendanceExport;
use App\Models\Attendance;
use App\Models\Permit;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyAttendanceReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.monthly-attendance-report';

    protected static ?string $navigationLabel = 'Laporan Bulanan';

    protected static ?string $title = 'Laporan Kehadiran Bulanan';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 2;

    public ?string $filter_type = 'monthly';
    public ?string $selected_month = null;
    public ?string $selected_year = null;
    public ?string $start_date = null;
    public ?string $end_date = null;

    public function mount(): void
    {
        $this->selected_month = now()->format('m');
        $this->selected_year = now()->format('Y');
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Radio::make('filter_type')
                    ->label('Filter Berdasarkan')
                    ->options([
                        'monthly' => 'Bulan & Tahun',
                        'range' => 'Range Tanggal',
                    ])
                    ->default('monthly')
                    ->inline()
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->resetTable()),

                Select::make('selected_month')
                    ->label('Bulan')
                    ->options([
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ])
                    ->default(now()->format('m'))
                    ->visible(fn ($get) => $get('filter_type') === 'monthly')
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->resetTable()),

                Select::make('selected_year')
                    ->label('Tahun')
                    ->options(function () {
                        $years = [];
                        $currentYear = (int) now()->format('Y');
                        for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++) {
                            $years[(string) $i] = (string) $i;
                        }
                        return $years;
                    })
                    ->default(now()->format('Y'))
                    ->visible(fn ($get) => $get('filter_type') === 'monthly')
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->resetTable()),

                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->default(now()->startOfMonth())
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->maxDate(now())
                    ->visible(fn ($get) => $get('filter_type') === 'range')
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->resetTable()),

                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->default(now())
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->maxDate(now())
                    ->visible(fn ($get) => $get('filter_type') === 'range')
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->resetTable()),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'teacher' => 'success',
                        'staff' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                TextColumn::make('department')
                    ->label('Departemen')
                    ->default('-')
                    ->sortable(),
                TextColumn::make('total_days')
                    ->label('Total Hari')
                    ->sortable(),
                TextColumn::make('present')
                    ->label('Hadir')
                    ->color('success')
                    ->sortable(),
                TextColumn::make('late')
                    ->label('Terlambat')
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('absent')
                    ->label('Tidak Hadir')
                    ->color('danger')
                    ->sortable(),
                TextColumn::make('on_leave')
                    ->label('Izin/Cuti')
                    ->color('info')
                    ->sortable(),
                TextColumn::make('attendance_rate')
                    ->label('% Kehadiran')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->badge()
                    ->color(fn ($state): string => $state >= 80 ? 'success' : 'warning')
                    ->sortable(),
            ])
            ->defaultSort('name', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    protected function getTableQuery(): Builder
    {
        $dateRange = $this->getDateRange();
        
        return User::query()
            ->select([
                'users.id',
                'users.name',
                'users.role',
                'users.department',
            ])
            ->selectRaw('COALESCE(present_count, 0) + COALESCE(late_count, 0) + COALESCE(absent_count, 0) + COALESCE(leave_days, 0) as total_days')
            ->selectRaw('COALESCE(present_count, 0) as present')
            ->selectRaw('COALESCE(late_count, 0) as late')
            ->selectRaw('COALESCE(absent_count, 0) as absent')
            ->selectRaw('COALESCE(leave_days, 0) as on_leave')
            ->selectRaw('
                CASE 
                    WHEN (COALESCE(present_count, 0) + COALESCE(late_count, 0) + COALESCE(absent_count, 0) + COALESCE(leave_days, 0)) > 0
                    THEN ROUND(((COALESCE(present_count, 0) + COALESCE(late_count, 0)) / (COALESCE(present_count, 0) + COALESCE(late_count, 0) + COALESCE(absent_count, 0) + COALESCE(leave_days, 0))) * 100, 1)
                    ELSE 0
                END as attendance_rate
            ')
            ->leftJoin(\DB::raw("(
                SELECT 
                    user_id,
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count
                FROM attendances
                WHERE date BETWEEN '{$dateRange['start']}' AND '{$dateRange['end']}'
                GROUP BY user_id
            ) as attendance_stats"), 'users.id', '=', 'attendance_stats.user_id')
            ->leftJoin(\DB::raw("(
                SELECT 
                    user_id,
                    SUM(
                        DATEDIFF(
                            LEAST(end_date, '{$dateRange['end']}'),
                            GREATEST(start_date, '{$dateRange['start']}')
                        ) + 1
                    ) as leave_days
                FROM permits
                WHERE status = 'approved'
                    AND start_date <= '{$dateRange['end']}'
                    AND end_date >= '{$dateRange['start']}'
                GROUP BY user_id
            ) as permit_stats"), 'users.id', '=', 'permit_stats.user_id');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $filename = $this->filter_type === 'monthly'
                        ? 'laporan-bulanan-' . $this->selected_month . '-' . $this->selected_year . '.xlsx'
                        : 'laporan-' . $this->start_date . '-to-' . $this->end_date . '.xlsx';

                    return Excel::download(
                        new MonthlyAttendanceExport(
                            $this->filter_type,
                            $this->selected_month,
                            $this->selected_year,
                            $this->start_date,
                            $this->end_date
                        ),
                        $filename
                    );
                }),
        ];
    }

    protected function getUserStats(int $userId): array
    {
        $dateRange = $this->getDateRange();
        
        $attendanceQuery = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$dateRange['start'], $dateRange['end']]);
        
        $present = (clone $attendanceQuery)->where('status', 'present')->count();
        $late = (clone $attendanceQuery)->where('status', 'late')->count();
        $absent = (clone $attendanceQuery)->where('status', 'absent')->count();
        
        $onLeave = Permit::where('user_id', $userId)
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
        
        $totalDays = $present + $late + $absent + $onLeave;
        $attendanceRate = $totalDays > 0 
            ? round((($present + $late) / $totalDays) * 100, 1)
            : 0;

        return [
            'total_days' => $totalDays,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'on_leave' => $onLeave,
            'attendance_rate' => $attendanceRate,
        ];
    }

    protected function getDateRange(): array
    {
        if ($this->filter_type === 'monthly') {
            $startDate = Carbon::createFromFormat('Y-m', $this->selected_year . '-' . $this->selected_month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $this->selected_year . '-' . $this->selected_month)->endOfMonth();
        } else {
            $startDate = Carbon::parse($this->start_date);
            $endDate = Carbon::parse($this->end_date);
        }

        return [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d'),
        ];
    }

    public function getSummary(): array
    {
        $users = User::all();
        $totalUsers = $users->count();
        
        $totalPresent = 0;
        $totalLate = 0;
        $totalAbsent = 0;
        $totalOnLeave = 0;
        
        foreach ($users as $user) {
            $stats = $this->getUserStats($user->id);
            $totalPresent += $stats['present'];
            $totalLate += $stats['late'];
            $totalAbsent += $stats['absent'];
            $totalOnLeave += $stats['on_leave'];
        }
        
        $totalDays = $totalPresent + $totalLate + $totalAbsent + $totalOnLeave;
        $avgAttendanceRate = $totalDays > 0 
            ? round((($totalPresent + $totalLate) / $totalDays) * 100, 1)
            : 0;
        
        return [
            'total_users' => $totalUsers,
            'total_present' => $totalPresent,
            'total_late' => $totalLate,
            'total_absent' => $totalAbsent,
            'total_on_leave' => $totalOnLeave,
            'avg_attendance_rate' => $avgAttendanceRate,
        ];
    }
}
