<?php

namespace App\Filament\Pages;

use App\Exports\DailyAttendanceExport;
use App\Models\Attendance;
use App\Models\Permit;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class DailyAttendanceReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static string $view = 'filament.pages.daily-attendance-report';

    protected static ?string $navigationLabel = 'Laporan Harian';

    protected static ?string $title = 'Laporan Kehadiran Harian';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 1;

    public ?string $selected_date = null;

    public function mount(): void
    {
        $this->selected_date = now()->format('Y-m-d');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('selected_date')
                    ->label('Tanggal')
                    ->default(now())
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->maxDate(now())
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->resetTable()),
            ])
            ->columns(1);
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
                TextColumn::make('attendance_status')
                    ->label('Status Kehadiran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Terlambat' => 'warning',
                        'Izin' => 'info',
                        'Cuti' => 'info',
                        'Sakit' => 'info',
                        'Tidak Hadir' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('clock_in')
                    ->label('Check In')
                    ->default('-')
                    ->sortable(),
                TextColumn::make('clock_out')
                    ->label('Check Out')
                    ->default('-')
                    ->sortable(),
                TextColumn::make('note')
                    ->label('Keterangan')
                    ->limit(30)
                    ->default('-')
                    ->sortable(),
            ])
            ->defaultSort('name', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    protected function getTableQuery(): Builder
    {
        $date = $this->selected_date ?: now()->format('Y-m-d');
        
        return User::query()
            ->select([
                'users.id',
                'users.name',
                'users.role',
                'users.department',
            ])
            ->selectRaw("
                CASE 
                    WHEN attendances.status = 'present' THEN 'Hadir'
                    WHEN attendances.status = 'late' THEN 'Terlambat'
                    WHEN attendances.status = 'absent' THEN 'Tidak Hadir'
                    WHEN permits.type = 'sick' THEN 'Sakit'
                    WHEN permits.type = 'leave' THEN 'Izin'
                    WHEN permits.type = 'vacation' THEN 'Cuti'
                    ELSE 'Tidak Hadir'
                END as attendance_status
            ")
            ->selectRaw("
                CASE 
                    WHEN attendances.clock_in IS NOT NULL THEN DATE_FORMAT(attendances.clock_in, '%H:%i:%s')
                    ELSE '-'
                END as clock_in
            ")
            ->selectRaw("
                CASE 
                    WHEN attendances.clock_out IS NOT NULL THEN DATE_FORMAT(attendances.clock_out, '%H:%i:%s')
                    ELSE '-'
                END as clock_out
            ")
            ->selectRaw("
                COALESCE(attendances.note, permits.reason, '-') as note
            ")
            ->leftJoin('attendances', function ($join) use ($date) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereDate('attendances.date', '=', $date);
            })
            ->leftJoin('permits', function ($join) use ($date) {
                $join->on('users.id', '=', 'permits.user_id')
                    ->whereDate('permits.start_date', '<=', $date)
                    ->whereDate('permits.end_date', '>=', $date)
                    ->where('permits.status', '=', 'approved');
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $filename = 'laporan-harian-' . $this->selected_date . '.xlsx';
                    
                    return Excel::download(
                        new DailyAttendanceExport($this->selected_date),
                        $filename
                    );
                }),
        ];
    }

    public function getSummary(): array
    {
        $date = $this->selected_date ?: now()->format('Y-m-d');
        
        $totalUsers = User::count();
        
        $present = Attendance::whereDate('date', $date)
            ->whereIn('status', ['present', 'late'])
            ->count();
        
        $onLeave = Permit::where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->count();
        
        $absent = $totalUsers - $present - $onLeave;
        
        return [
            'total' => $totalUsers,
            'present' => $present,
            'on_leave' => $onLeave,
            'absent' => $absent,
            'attendance_rate' => $totalUsers > 0 ? round(($present / $totalUsers) * 100, 1) : 0,
        ];
    }
}
