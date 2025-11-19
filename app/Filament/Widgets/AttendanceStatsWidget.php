<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendanceStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $present = Attendance::where('date', $today)
            ->where('status', 'present')
            ->count();

        $late = Attendance::where('date', $today)
            ->where('status', 'late')
            ->count();

        $permit = Attendance::where('date', $today)
            ->whereIn('status', ['permit', 'sick'])
            ->count();

        $alpha = Attendance::where('date', $today)
            ->where('status', 'alpha')
            ->count();

        return [
            Stat::make('Present Today', $present)
                ->description('On time check-ins')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Late Today', $late)
                ->description('Late arrivals')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('On Leave', $permit)
                ->description('Permit/Sick leave')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),

            Stat::make('Alpha Today', $alpha)
                ->description('Absent without notice')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
