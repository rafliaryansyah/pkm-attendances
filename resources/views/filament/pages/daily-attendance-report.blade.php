<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                Filter Tanggal
            </x-slot>
            
            <form wire:submit.prevent>
                {{ $this->form }}
            </form>
        </x-filament::section>

        @php
            $summary = $this->getSummary();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-filament::section class="text-center">
                <div class="text-3xl font-bold text-primary-600">{{ $summary['total'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Karyawan</div>
            </x-filament::section>

            <x-filament::section class="text-center">
                <div class="text-3xl font-bold text-success-600">{{ $summary['present'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Hadir</div>
            </x-filament::section>

            <x-filament::section class="text-center">
                <div class="text-3xl font-bold text-warning-600">{{ $summary['on_leave'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Izin/Cuti</div>
            </x-filament::section>

            <x-filament::section class="text-center">
                <div class="text-3xl font-bold text-danger-600">{{ $summary['absent'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tidak Hadir</div>
            </x-filament::section>
        </div>

        <x-filament::section>
            <x-slot name="heading">
                Data Kehadiran Hari Ini
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
