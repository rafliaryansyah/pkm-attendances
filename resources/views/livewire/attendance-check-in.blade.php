<div class="min-h-screen bg-gray-50">
    <div class="max-w-md mx-auto p-4 space-y-4">
        <!-- Banner Slider -->
        @if($banners && $banners->count() > 0)
        <div class="relative overflow-hidden rounded-lg shadow-lg" style="height: 200px;">
            @foreach($banners as $index => $banner)
            <div class="banner-slide absolute w-full h-full transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                    <p class="text-white text-sm font-semibold">{{ $banner->title }}</p>
                </div>
            </div>
            @endforeach

            @if($banners->count() > 1)
            <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                @foreach($banners as $index => $banner)
                <button class="banner-indicator w-2 h-2 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/50' }}" data-index="{{ $index }}"></button>
                @endforeach
            </div>
            @endif
        </div>
        @else
        <!-- Default Header if no banners -->
        <div class="bg-indigo-600 text-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold">Absensi SMK</h1>
            <p class="text-indigo-100">{{ Auth::user()->name }}</p>
            <p class="text-indigo-200 text-sm">{{ Auth::user()->department }}</p>
        </div>
        @endif

        <!-- Alert Messages -->
        @if($message)
        <div class="p-4 rounded-lg {{ $status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $message }}
        </div>
        @endif

        <!-- Main Action Button -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            @if(!$todayAttendance)
                <button wire:click="clockIn"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-lg transition">
                    Check In
                </button>
            @elseif(!$todayAttendance->clock_out)
                <button wire:click="clockOut"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg text-lg transition">
                    Check Out
                </button>
            @else
                <div class="text-center text-gray-600">
                    <p class="font-semibold">Absensi Hari Ini Selesai</p>
                    <p class="text-sm mt-2">Check In: {{ $todayAttendance->clock_in->format('H:i') }}</p>
                    <p class="text-sm">Check Out: {{ $todayAttendance->clock_out->format('H:i') }}</p>
                </div>
            @endif
        </div>

        <!-- Today's Status -->
        @if($todayAttendance)
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-semibold mb-2">Status Hari Ini</h3>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    @if($todayAttendance->status === 'Hadir') bg-green-100 text-green-800
                    @elseif($todayAttendance->status === 'Telat') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($todayAttendance->status) }}
                </span>
            </div>
        </div>
        @endif

        <!-- Recent History -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-semibold mb-3">Riwayat Absensi (5 Hari Terakhir)</h3>
            <div class="space-y-2">
                @forelse($recentAttendances as $attendance)
                <div class="flex justify-between items-center border-b pb-2">
                    <div>
                        <p class="font-medium">{{ $attendance->date->translatedFormat('l, d M Y') }}</p>
                        <p class="text-sm text-gray-600">
                            {{ $attendance->clock_in->format('H:i') }} -
                            {{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : 'Belum Check Out' }}
                        </p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        @if($attendance->status === 'Hadir') bg-green-100 text-green-800
                        @elseif($attendance->status === 'Telat') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($attendance->status) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada riwayat absensi</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Geolocation Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    (position) => {
                        @this.set('latitude', position.coords.latitude);
                        @this.set('longitude', position.coords.longitude);
                    },
                    (error) => {
                        console.error('Geolocation error:', error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            }
        });
    </script>

    <!-- Banner Slider Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.banner-slide');
            const indicators = document.querySelectorAll('.banner-indicator');

            if (slides.length > 1) {
                let currentIndex = 0;

                function showSlide(index) {
                    slides.forEach((slide, i) => {
                        if (i === index) {
                            slide.classList.remove('opacity-0');
                            slide.classList.add('opacity-100');
                        } else {
                            slide.classList.remove('opacity-100');
                            slide.classList.add('opacity-0');
                        }
                    });

                    indicators.forEach((indicator, i) => {
                        if (i === index) {
                            indicator.classList.remove('bg-white/50');
                            indicator.classList.add('bg-white');
                        } else {
                            indicator.classList.remove('bg-white');
                            indicator.classList.add('bg-white/50');
                        }
                    });
                }

                function nextSlide() {
                    currentIndex = (currentIndex + 1) % slides.length;
                    showSlide(currentIndex);
                }

                setInterval(nextSlide, 5000);

                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => {
                        currentIndex = index;
                        showSlide(currentIndex);
                    });
                });
            }
        });
    </script>
</div>
