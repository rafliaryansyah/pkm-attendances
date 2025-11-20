<div class="min-h-screen bg-gradient-to-br from-pink-50 to-blue-50 pb-24">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-pink-100 to-purple-100 px-6 pt-8 pb-6 rounded-b-3xl shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('mobile.check-in-out') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Riwayat Absensi 📋</h1>
            </div>
        </div>
        <p class="text-sm text-gray-600">{{ auth()->user()->name }}</p>
    </div>

    {{-- Month & Year Filter --}}
    <div class="mx-4 mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-4 border-2 border-gray-100">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Filter Periode</label>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Bulan</label>
                    <select wire:model.live="selectedMonth" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        @foreach($months as $key => $month)
                            <option value="{{ $key }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Tahun</label>
                    <select wire:model.live="selectedYear" class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="mx-4 mt-6">
        <h3 class="text-gray-800 font-bold text-lg mb-4">Statistik Bulan Ini</h3>
        <div class="grid grid-cols-2 gap-3">
            {{-- Present --}}
            <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-600 font-medium">Hadir</p>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_present'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Hari</p>
            </div>

            {{-- Late --}}
            <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-600 font-medium">Terlambat</p>
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_late'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Hari</p>
            </div>

            {{-- Permit --}}
            <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-600 font-medium">Izin/Sakit</p>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_permit'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Hari</p>
            </div>

            {{-- Performance --}}
            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl p-4 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-white font-medium">Performa</p>
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-white">
                    @php
                        $total = $stats['total_present'] + $stats['total_permit'];
                        $performance = $total > 0 ? round(($stats['total_present'] / $total) * 100) : 0;
                    @endphp
                    {{ $performance }}%
                </p>
                <p class="text-xs text-white opacity-90 mt-1">Kehadiran</p>
            </div>
        </div>
    </div>

    {{-- Attendance List --}}
    <div class="mx-4 mt-6">
        <h3 class="text-gray-800 font-bold text-lg mb-4">Detail Absensi</h3>

        @if($attendances->count() > 0)
            <div class="space-y-3">
                @foreach($attendances as $attendance)
                    <div class="bg-white rounded-2xl shadow-lg p-5 border-2 border-gray-100">
                        {{-- Date Header --}}
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">
                                    {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                </h4>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('dddd') }}
                                </p>
                            </div>
                            <span class="px-3 py-1.5 text-xs font-bold rounded-full shadow-sm
                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $attendance->status === 'late' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $attendance->status === 'permit' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $attendance->status === 'sick' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $attendance->status === 'alpha' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $attendance->status === 'present' ? '✓ Hadir' : '' }}
                                {{ $attendance->status === 'late' ? '⏰ Terlambat' : '' }}
                                {{ $attendance->status === 'permit' ? '📝 Izin' : '' }}
                                {{ $attendance->status === 'sick' ? '🏥 Sakit' : '' }}
                                {{ $attendance->status === 'alpha' ? '✗ Alpha' : '' }}
                            </span>
                        </div>

                        {{-- Time Grid --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            {{-- Check In --}}
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-3 border-2 border-green-100">
                                <div class="flex items-center text-gray-600 text-xs mb-1.5">
                                    <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Masuk
                                </div>
                                <p class="text-2xl font-bold text-green-700">
                                    {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '--:--' }}
                                </p>
                            </div>

                            {{-- Check Out --}}
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-3 border-2 border-blue-100">
                                <div class="flex items-center text-gray-600 text-xs mb-1.5">
                                    <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Pulang
                                </div>
                                <p class="text-2xl font-bold text-blue-700">
                                    {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '--:--' }}
                                </p>
                            </div>
                        </div>

                        {{-- Additional Info --}}
                        @if($attendance->note || $attendance->is_revision)
                            <div class="pt-3 border-t-2 border-gray-100">
                                @if($attendance->note)
                                    <div class="mb-2">
                                        <p class="text-xs text-gray-500 mb-1">Catatan:</p>
                                        <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded-lg">{{ $attendance->note }}</p>
                                    </div>
                                @endif

                                @if($attendance->is_revision)
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-orange-700 bg-orange-100 rounded-full">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Data Revisi
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $attendances->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center border-2 border-gray-100">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Data</h3>
                <p class="text-sm text-gray-500">Belum ada riwayat absensi untuk bulan ini</p>
            </div>
        @endif
    </div>

    {{-- Bottom Navigation --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-100 shadow-2xl">
        <div class="flex items-center justify-around py-3 px-6 max-w-lg mx-auto">
            <a href="{{ route('mobile.check-in-out') }}" class="flex flex-col items-center space-y-1 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-xs font-medium">Beranda</span>
            </a>

            <a href="{{ route('mobile.history') }}" class="flex flex-col items-center space-y-1 text-blue-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Riwayat</span>
            </a>

            <a href="#" class="flex flex-col items-center space-y-1 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="text-xs font-medium">Izin</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center space-y-1 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Profil</span>
            </a>
        </div>
    </div>
</div>
