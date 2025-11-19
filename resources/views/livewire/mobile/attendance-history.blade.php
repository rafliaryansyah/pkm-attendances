<div class="min-h-screen bg-gray-50 pb-20">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-bold">Riwayat Absensi</h1>
            <a href="{{ route('mobile.check-in-out') }}" class="text-white hover:text-blue-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </a>
        </div>
        <p class="text-sm opacity-90">{{ auth()->user()->name }}</p>
    </div>

    {{-- Month & Year Filter --}}
    <div class="mx-4 mt-4 bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                <select wire:model.live="selectedMonth" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($months as $key => $month)
                        <option value="{{ $key }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                <select wire:model.live="selectedYear" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="mx-4 mt-4 grid grid-cols-3 gap-3">
        <div class="bg-white rounded-lg shadow-md p-3 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['total_present'] }}</div>
            <div class="text-xs text-gray-600 mt-1">Hadir</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-3 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['total_late'] }}</div>
            <div class="text-xs text-gray-600 mt-1">Terlambat</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-3 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_permit'] }}</div>
            <div class="text-xs text-gray-600 mt-1">Izin/Sakit</div>
        </div>
    </div>

    {{-- Attendance List --}}
    <div class="mx-4 mt-4">
        @if($attendances->count() > 0)
            <div class="space-y-3">
                @foreach($attendances as $attendance)
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('dddd') }}
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $attendance->status === 'permit' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $attendance->status === 'sick' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $attendance->status === 'alpha' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $attendance->status === 'present' ? 'Hadir' : '' }}
                                {{ $attendance->status === 'late' ? 'Terlambat' : '' }}
                                {{ $attendance->status === 'permit' ? 'Izin' : '' }}
                                {{ $attendance->status === 'sick' ? 'Sakit' : '' }}
                                {{ $attendance->status === 'alpha' ? 'Alpha' : '' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Check In</div>
                                <div class="flex items-center text-sm font-medium text-gray-900">
                                    <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Check Out</div>
                                <div class="flex items-center text-sm font-medium text-gray-900">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}
                                </div>
                            </div>
                        </div>

                        @if($attendance->note)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">Catatan</div>
                                <div class="text-sm text-gray-700">{{ $attendance->note }}</div>
                            </div>
                        @endif

                        @if($attendance->is_revision)
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-orange-800 bg-orange-100 rounded-full">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Revisi
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500">Belum ada data absensi untuk bulan ini</p>
            </div>
        @endif
    </div>
</div>
