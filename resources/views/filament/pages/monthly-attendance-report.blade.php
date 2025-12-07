<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                Filter Periode
            </x-slot>
            
            <form wire:submit.prevent>
                {{ $this->form }}
            </form>
        </x-filament::section>

        @php
            $summary = $this->getSummary();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-filament::section class="text-center">
                <div class="text-3xl font-bold text-primary-600">{{ $summary['total_users'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Karyawan</div>
            </x-filament::section>

            <x-filament::section class="text-center">
                <div class="text-3xl font-bold text-success-600">{{ $summary['total_present'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Kehadiran</div>
            </x-filament::section>

            <x-filament::section class="text-center">
                <div class="text-3xl font-bold text-info-600">{{ $summary['avg_attendance_rate'] }}%</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rata-rata Kehadiran</div>
            </x-filament::section>
        </div>

        <x-filament::section>
            <x-slot name="heading">
                Rekapitulasi Kehadiran Bulanan
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
