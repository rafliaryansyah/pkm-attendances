<x-filament-panels::page>
    <div class="space-y-6">
        @php
            $location = $this->getCurrentLocation();
        @endphp

        @if($location)
            <x-filament::section>
                <x-slot name="heading">
                    Status Lokasi Absensi
                </x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lokasi:</dt>
                                <dd class="text-sm font-semibold">{{ $location['name'] ?? 'Tidak ada' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Latitude:</dt>
                                <dd class="text-sm font-mono">{{ $location['lat'] }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Longitude:</dt>
                                <dd class="text-sm font-mono">{{ $location['long'] }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Radius:</dt>
                                <dd class="text-sm font-semibold">{{ $location['radius'] }} meter</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div class="bg-success-50 dark:bg-success-950 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-success-600 dark:text-success-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-success-800 dark:text-success-200">
                                    Lokasi Aktif
                                </h3>
                                <p class="mt-1 text-sm text-success-700 dark:text-success-300">
                                    User dapat melakukan absensi dengan lokasi yang telah ditentukan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($location['lat'] && $location['long'])
                    <div class="mt-4">
                        <div class="relative w-full h-64 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden">
                            <iframe
                                width="100%"
                                height="100%"
                                frameborder="0"
                                style="border:0"
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps/embed/v1/place?key=&q={{ $location['lat'] }},{{ $location['long'] }}&zoom=17"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center">
                            <a href="https://www.google.com/maps?q={{ $location['lat'] }},{{ $location['long'] }}" 
                               target="_blank" 
                               class="text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                Buka di Google Maps →
                            </a>
                        </p>
                    </div>
                @endif
            </x-filament::section>
        @else
            <x-filament::section>
                <div class="bg-warning-50 dark:bg-warning-950 p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-warning-600 dark:text-warning-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-warning-800 dark:text-warning-200">
                                Lokasi Belum Ditentukan
                            </h3>
                            <p class="mt-1 text-sm text-warning-700 dark:text-warning-300">
                                User tidak dapat melakukan absensi sampai lokasi ditentukan. Silakan atur lokasi dan radius di bawah ini.
                            </p>
                        </div>
                    </div>
                </div>
            </x-filament::section>
        @endif

        <x-filament::section>
            <x-slot name="heading">
                Form Pengaturan
            </x-slot>
            
            <x-slot name="description">
                Gunakan Google Maps untuk mendapatkan koordinat yang akurat. Klik kanan pada lokasi di Google Maps dan pilih koordinat untuk menyalin.
            </x-slot>

            <form wire:submit.prevent="save">
                {{ $this->form }}
                
                <div class="mt-6 flex gap-3">
                    @foreach($this->getFormActions() as $action)
                        {{ $action }}
                    @endforeach
                </div>
            </form>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Cara Mendapatkan Koordinat
            </x-slot>

            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <li>Buka <a href="https://www.google.com/maps" target="_blank" class="text-primary-600 hover:underline">Google Maps</a></li>
                <li>Cari dan temukan lokasi sekolah/kantor yang diinginkan</li>
                <li>Klik kanan pada titik lokasi tersebut</li>
                <li>Pilih koordinat (angka pertama) yang muncul untuk menyalin</li>
                <li>Paste koordinat tersebut (format: -6.200000, 106.816666)</li>
                <li>Pisahkan latitude (angka pertama) dan longitude (angka kedua)</li>
                <li>Atur radius sesuai kebutuhan (disarankan 50-200 meter)</li>
            </ol>
        </x-filament::section>
    </div>
</x-filament-panels::page>
