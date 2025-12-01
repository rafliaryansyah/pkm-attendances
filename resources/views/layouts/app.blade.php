<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4F46E5">

    <title>{{ config('app.name', 'SMK Attendance') }}</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="/images/icon-192x192.png">
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen pb-20">
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Bottom Navigation Bar -->
    @auth
    <nav class="bg-white shadow-lg border-t border-gray-200 fixed bottom-0 left-0 right-0 z-50" style="position: fixed !important; bottom: 0 !important;">
        <div class="max-w-md mx-auto px-4">
            <div class="flex justify-around items-center py-3">
                <a href="{{ route('home') }}" class="flex flex-col items-center justify-center min-w-[60px] py-2 px-3 text-gray-600 hover:text-indigo-600 transition-colors {{ request()->routeIs('home') ? 'text-indigo-600' : '' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="text-xs font-medium">Home</span>
                </a>

                <a href="{{ route('permits') }}" class="flex flex-col items-center justify-center min-w-[60px] py-2 px-3 text-gray-600 hover:text-indigo-600 transition-colors {{ request()->routeIs('permits') ? 'text-indigo-600' : '' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-xs font-medium">Izin</span>
                </a>

                <a href="{{ route('revisions') }}" class="flex flex-col items-center justify-center min-w-[60px] py-2 px-3 text-gray-600 hover:text-indigo-600 transition-colors {{ request()->routeIs('revisions') ? 'text-indigo-600' : '' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="text-xs font-medium">Revisi</span>
                </a>

                <a href="{{ route('user.profile') }}" class="flex flex-col items-center justify-center min-w-[60px] py-2 px-3 text-gray-600 hover:text-indigo-600 transition-colors {{ request()->routeIs('user.profile') ? 'text-indigo-600' : '' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-xs font-medium">Profile</span>
                </a>
            </div>
        </div>
    </nav>
    @endauth

    @livewireScripts

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered'))
                    .catch(err => console.log('Service Worker registration failed'));
            });
        }
    </script>
</body>
</html>
