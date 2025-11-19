<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 shadow-lg">
        <h1 class="text-2xl font-bold">SMK Attendance</h1>
        <p class="text-sm opacity-90">{{ auth()->user()->name }}</p>
        <p class="text-xs opacity-75">{{ now()->format('l, d F Y') }}</p>
    </div>

    {{-- Messages --}}
    @if($message)
        <div class="mx-4 mt-4 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            <p class="text-sm font-medium">{{ $message }}</p>
        </div>
    @endif

    {{-- Today's Attendance Status --}}
    @if($todayAttendance)
        <div class="mx-4 mt-4 bg-white rounded-lg shadow-md p-4">
            <h3 class="font-semibold text-gray-700 mb-3">Today's Attendance</h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Clock In:</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $todayAttendance->clock_in->format('H:i') }}
                    </span>
                </div>
                @if($todayAttendance->clock_out)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Clock Out:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $todayAttendance->clock_out->format('H:i') }}
                        </span>
                    </div>
                @endif
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        {{ $todayAttendance->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $todayAttendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                        {{ ucfirst($todayAttendance->status) }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- Check In/Out Button --}}
    <div class="mx-4 mt-6">
        @if($distance !== null)
            <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Distance from school:</span>
                    <span class="text-blue-600 font-bold">{{ round($distance) }} meters</span>
                </p>
                @if($distance > 80)
                    <p class="text-xs text-red-600 mt-1">⚠️ You are outside the allowed radius (80m)</p>
                @else
                    <p class="text-xs text-green-600 mt-1">✓ Within allowed radius</p>
                @endif
            </div>
        @endif

        @if(!$hasCheckedIn)
            <button
                wire:click="checkIn"
                wire:loading.attr="disabled"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-6 rounded-xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all transform active:scale-95">
                <div wire:loading.remove wire:target="checkIn">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-lg">Check In</span>
                </div>
                <div wire:loading wire:target="checkIn">
                    <svg class="animate-spin h-6 w-6 mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </button>
        @elseif(!$hasCheckedOut)
            <button
                wire:click="checkOut"
                wire:loading.attr="disabled"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-6 rounded-xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all transform active:scale-95">
                <div wire:loading.remove wire:target="checkOut">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="text-lg">Check Out</span>
                </div>
                <div wire:loading wire:target="checkOut">
                    <svg class="animate-spin h-6 w-6 mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </button>
        @else
            <div class="w-full bg-gray-200 text-gray-600 font-bold py-6 rounded-xl text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-lg">Attendance Complete</span>
            </div>
        @endif
    </div>

    {{-- Today's Leave/Permit Users --}}
    @if($todayLeave->count() > 0)
        <div class="mx-4 mt-6 bg-white rounded-lg shadow-md p-4">
            <h3 class="font-semibold text-gray-700 mb-3">On Leave Today</h3>
            <div class="space-y-2">
                @foreach($todayLeave as $leave)
                    <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600">{{ Str::substr($leave->user->name, 0, 2) }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $leave->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($leave->type) }} - {{ $leave->start_date->format('d M') }} to {{ $leave->end_date->format('d M') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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
                    alert('Please enable location services to use attendance features.');
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
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    </script>
</div>
