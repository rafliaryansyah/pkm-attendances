<div class="min-h-screen bg-gradient-to-br from-pink-50 to-blue-50 pb-24">
    {{-- Header with Profile --}}
    <div class="bg-gradient-to-br from-pink-100 to-purple-100 px-6 pt-8 pb-6 rounded-b-3xl shadow-lg">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-gray-800 font-bold text-lg">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-600 text-sm">{{ auth()->user()->department ?? 'Staff' }}</p>
                </div>
            </div>
            <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </button>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Let's get to work! 💼</h1>

        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ now()->isoFormat('dddd, D MMM Y') }}
            </div>
            @if($distance !== null)
                <div class="flex items-center {{ $distance <= 80 ? 'text-green-700' : 'text-red-700' }}">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ round($distance) }}m
                </div>
            @endif
        </div>
    </div>

    {{-- Alert Messages --}}
    @if($message)
        <div class="mx-4 mt-4 p-4 rounded-2xl shadow-lg {{ $messageType === 'success' ? 'bg-green-50 border-2 border-green-200' : 'bg-red-50 border-2 border-red-200' }}">
            <div class="flex items-center">
                @if($messageType === 'success')
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @else
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @endif
                <p class="text-sm font-medium {{ $messageType === 'success' ? 'text-green-800' : 'text-red-800' }}">{{ $message }}</p>
            </div>
        </div>
    @endif

    {{-- Main Attendance Card --}}
    <div class="mx-4 mt-6">
        <div class="bg-white rounded-3xl shadow-xl p-6 border-2 border-gray-100">
            {{-- Status Badge --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-700 font-semibold text-lg">Mode Saat Ini</h3>
                @if($todayAttendance)
                    <span class="flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Terkoneksi
                    </span>
                @else
                    <span class="flex items-center px-3 py-1.5 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">
                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                        Belum Absen
                    </span>
                @endif
            </div>

            {{-- Time Display --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                {{-- Check In --}}
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-4 border-2 {{ $hasCheckedIn ? 'border-green-200' : 'border-gray-200' }}">
                    <div class="flex items-center text-gray-600 text-xs mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Masuk
                    </div>
                    <div class="{{ $hasCheckedIn ? 'text-green-600' : 'text-gray-400' }} text-3xl font-bold">
                        @if($todayAttendance && $todayAttendance->clock_in)
                            {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}
                        @else
                            --:--
                        @endif
                    </div>
                    @if($todayAttendance && $todayAttendance->status === 'late')
                        <span class="inline-block mt-2 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Terlambat</span>
                    @endif
                </div>

                {{-- Check Out --}}
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-4 border-2 {{ $hasCheckedOut ? 'border-blue-200' : 'border-gray-200' }}">
                    <div class="flex items-center text-gray-600 text-xs mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Pulang
                    </div>
                    <div class="{{ $hasCheckedOut ? 'text-blue-600' : 'text-gray-400' }} text-3xl font-bold">
                        @if($todayAttendance && $todayAttendance->clock_out)
                            {{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}
                        @else
                            {{ auth()->user()->work_end_time ? \Carbon\Carbon::parse(auth()->user()->work_end_time)->format('H:i') : '--:--' }}
                        @endif
                    </div>
                    @if($todayAttendance && $todayAttendance->clock_out)
                        <span class="inline-block mt-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Selesai</span>
                    @endif
                </div>
            </div>

            {{-- Action Button --}}
            @if(!$hasCheckedIn)
                <button
                    wire:click="checkIn"
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 rounded-2xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all transform active:scale-95 flex items-center justify-center">
                    <div wire:loading.remove wire:target="checkIn" class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Absen Masuk
                    </div>
                    <div wire:loading wire:target="checkIn">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            @elseif(!$hasCheckedOut)
                <button
                    wire:click="checkOut"
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-4 rounded-2xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all transform active:scale-95 flex items-center justify-center">
                    <div wire:loading.remove wire:target="checkOut" class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Absen Pulang
                    </div>
                    <div wire:loading wire:target="checkOut">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            @else
                <div class="w-full bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 font-bold py-4 rounded-2xl text-center flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Absensi Hari Ini Selesai
                </div>
            @endif

            {{-- GPS Info --}}
            @if($distance !== null)
                <div class="mt-4 p-3 bg-gradient-to-r {{ $distance <= 80 ? 'from-green-50 to-emerald-50 border-green-200' : 'from-red-50 to-orange-50 border-red-200' }} rounded-xl border-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 {{ $distance <= 80 ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 {{ $distance <= 80 ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Jarak dari Sekolah</p>
                                <p class="text-sm font-bold {{ $distance <= 80 ? 'text-green-700' : 'text-red-700' }}">{{ round($distance) }} meter</p>
                            </div>
                        </div>
                        <div>
                            @if($distance <= 80)
                                <span class="px-3 py-1 bg-green-500 text-white text-xs rounded-full font-medium">✓ Dalam Jangkauan</span>
                            @else
                                <span class="px-3 py-1 bg-red-500 text-white text-xs rounded-full font-medium">⚠ Terlalu Jauh</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Statistics Section --}}
    <div class="mx-4 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-800 font-bold text-lg">Statistik Kehadiran</h3>
            <span class="text-xs text-gray-500">{{ now()->format('F Y') }}</span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            {{-- Present Days --}}
            <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-600">Hadir</p>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">
                    {{ \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereMonth('date', now()->month)
                        ->whereIn('status', ['present', 'late'])
                        ->count() }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Hari</p>
            </div>

            {{-- Late Days --}}
            <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-600">Terlambat</p>
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">
                    {{ \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereMonth('date', now()->month)
                        ->where('status', 'late')
                        ->count() }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Hari</p>
            </div>

            {{-- Permit/Sick Days --}}
            <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-600">Izin/Sakit</p>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">
                    {{ \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereMonth('date', now()->month)
                        ->whereIn('status', ['permit', 'sick'])
                        ->count() }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Hari</p>
            </div>

            {{-- Alpha Days --}}
            <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-gray-600">Alpha</p>
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">
                    {{ \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereMonth('date', now()->month)
                        ->where('status', 'alpha')
                        ->count() }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Hari</p>
            </div>
        </div>

        {{-- Success Banner if checked out --}}
        @if($hasCheckedOut)
            <div class="mt-4 p-4 bg-gradient-to-r from-green-400 to-emerald-500 rounded-2xl shadow-lg">
                <p class="text-white font-bold text-center text-lg">✨ Absenmu berhasil! ✨</p>
                <p class="text-white text-center text-sm opacity-90 mt-1">Semangat dan sampai jumpa besok!</p>
            </div>
        @endif
    </div>

    {{-- On Leave Today --}}
    @if($todayLeave->count() > 0)
        <div class="mx-4 mt-6 mb-6">
            <h3 class="text-gray-800 font-bold text-lg mb-4">Sedang Cuti/Izin</h3>
            <div class="space-y-3">
                @foreach($todayLeave as $leave)
                    <div class="bg-white rounded-2xl p-4 shadow-lg border-2 border-gray-100 flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                            {{ strtoupper(substr($leave->user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $leave->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($leave->type) }} • {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M') }}</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">{{ ucfirst($leave->type) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Bottom Navigation --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-100 shadow-2xl">
        <div class="flex items-center justify-around py-3 px-6 max-w-lg mx-auto">
            <a href="{{ route('mobile.check-in-out') }}" class="flex flex-col items-center space-y-1 text-blue-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Beranda</span>
            </a>

            <a href="{{ route('mobile.history') }}" class="flex flex-col items-center space-y-1 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

    {{-- Geolocation Script --}}
    <script>
        // Get user's location when component loads
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    @this.set('latitude', position.coords.latitude);
                    @this.set('longitude', position.coords.longitude);
                },
                function(error) {
                    console.error('Geolocation error:', error);
                }
            );

            // Update location every 10 seconds
            setInterval(function() {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        @this.set('latitude', position.coords.latitude);
                        @this.set('longitude', position.coords.longitude);
                    }
                );
            }, 10000);
        }
    </script>
</div>
