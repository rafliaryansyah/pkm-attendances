<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4F46E5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Absensi SMK">
    <meta name="application-name" content="Absensi SMK">
    <meta name="msapplication-TileColor" content="#4F46E5">
    <meta name="msapplication-tap-highlight" content="no">

    <title>{{ config('app.name', 'SMK Attendance') }}</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">

    <!-- Icons -->
    <link rel="icon" type="image/svg+xml" href="/images/icon.svg">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/icon-192.png">
    <link rel="apple-touch-icon" href="/images/icon-192.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- PWA Install Banner Styles -->
    <style>
        .pwa-install-banner {
            display: none;
            position: fixed;
            bottom: 80px;
            left: 16px;
            right: 16px;
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            padding: 16px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4);
            z-index: 9999;
            animation: slideUp 0.3s ease-out;
        }
        .pwa-install-banner.show {
            display: block;
        }
        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .pwa-install-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .pwa-install-icon {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .pwa-install-icon svg {
            width: 28px;
            height: 28px;
            color: #4F46E5;
        }
        .pwa-install-text {
            flex: 1;
        }
        .pwa-install-text h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .pwa-install-text p {
            font-size: 12px;
            opacity: 0.9;
        }
        .pwa-install-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }
        .pwa-install-btn {
            flex: 1;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: transform 0.2s, opacity 0.2s;
        }
        .pwa-install-btn:active {
            transform: scale(0.98);
        }
        .pwa-install-btn.primary {
            background: white;
            color: #4F46E5;
        }
        .pwa-install-btn.secondary {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .pwa-ios-instructions {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideUp 0.3s ease-out;
        }
        .pwa-ios-instructions.show {
            display: block;
        }
        .pwa-ios-instructions h4 {
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 16px;
            text-align: center;
        }
        .pwa-ios-step {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .pwa-ios-step:last-child {
            border-bottom: none;
        }
        .pwa-ios-step-num {
            width: 28px;
            height: 28px;
            background: #4F46E5;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            flex-shrink: 0;
        }
        .pwa-ios-step p {
            font-size: 14px;
            color: #4B5563;
        }
        .pwa-ios-close {
            width: 100%;
            padding: 12px;
            margin-top: 16px;
            background: #F3F4F6;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #4B5563;
            cursor: pointer;
        }
        .pwa-ios-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
        }
        .pwa-ios-overlay.show {
            display: block;
        }
    </style>
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

    <!-- PWA Install Banner -->
    <div id="pwa-install-banner" class="pwa-install-banner">
        <div class="pwa-install-content">
            <div class="pwa-install-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L12 14M12 14L8 10M12 14L16 10"/>
                    <path d="M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                </svg>
            </div>
            <div class="pwa-install-text">
                <h4>Install Absensi SMK</h4>
                <p>Akses lebih cepat langsung dari home screen HP Anda</p>
            </div>
        </div>
        <div class="pwa-install-actions">
            <button class="pwa-install-btn primary" id="pwa-install-btn">Install Sekarang</button>
            <button class="pwa-install-btn secondary" id="pwa-dismiss-btn">Nanti</button>
        </div>
    </div>

    <!-- iOS Install Instructions -->
    <div id="pwa-ios-overlay" class="pwa-ios-overlay"></div>
    <div id="pwa-ios-instructions" class="pwa-ios-instructions">
        <h4>Install Absensi SMK di iPhone</h4>
        <div class="pwa-ios-step">
            <span class="pwa-ios-step-num">1</span>
            <p>Tap tombol <strong>Share</strong> (kotak dengan panah ke atas) di bawah Safari</p>
        </div>
        <div class="pwa-ios-step">
            <span class="pwa-ios-step-num">2</span>
            <p>Scroll ke bawah dan tap <strong>"Add to Home Screen"</strong></p>
        </div>
        <div class="pwa-ios-step">
            <span class="pwa-ios-step-num">3</span>
            <p>Tap <strong>"Add"</strong> di pojok kanan atas</p>
        </div>
        <button class="pwa-ios-close" id="pwa-ios-close">Mengerti</button>
    </div>

    <!-- PWA Service Worker & Install Logic -->
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => {
                        console.log('Service Worker registered successfully');
                        reg.update();
                    })
                    .catch(err => console.log('Service Worker registration failed:', err));
            });
        }

        // PWA Install Prompt Logic
        let deferredPrompt;
        const installBanner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const dismissBtn = document.getElementById('pwa-dismiss-btn');
        const iosOverlay = document.getElementById('pwa-ios-overlay');
        const iosInstructions = document.getElementById('pwa-ios-instructions');
        const iosClose = document.getElementById('pwa-ios-close');

        // Check if already installed
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                           window.navigator.standalone === true;
        
        // Check if dismissed recently (24 hours)
        const dismissedTime = localStorage.getItem('pwa-dismissed');
        const isDismissed = dismissedTime && (Date.now() - parseInt(dismissedTime)) < 24 * 60 * 60 * 1000;

        // Detect iOS
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

        // Handle Android/Chrome install prompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            if (!isStandalone && !isDismissed) {
                setTimeout(() => {
                    installBanner.classList.add('show');
                }, 3000);
            }
        });

        // Handle iOS - show custom instructions
        if (isIOS && isSafari && !isStandalone && !isDismissed) {
            setTimeout(() => {
                installBanner.classList.add('show');
            }, 3000);
        }

        // Install button click
        installBtn.addEventListener('click', async () => {
            if (isIOS) {
                // Show iOS instructions
                installBanner.classList.remove('show');
                iosOverlay.classList.add('show');
                iosInstructions.classList.add('show');
            } else if (deferredPrompt) {
                // Show native install prompt
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response: ${outcome}`);
                deferredPrompt = null;
                installBanner.classList.remove('show');
            }
        });

        // Dismiss button click
        dismissBtn.addEventListener('click', () => {
            installBanner.classList.remove('show');
            localStorage.setItem('pwa-dismissed', Date.now().toString());
        });

        // iOS instructions close
        iosClose.addEventListener('click', () => {
            iosOverlay.classList.remove('show');
            iosInstructions.classList.remove('show');
            localStorage.setItem('pwa-dismissed', Date.now().toString());
        });

        iosOverlay.addEventListener('click', () => {
            iosOverlay.classList.remove('show');
            iosInstructions.classList.remove('show');
        });

        // Hide banner when installed
        window.addEventListener('appinstalled', () => {
            console.log('PWA installed');
            installBanner.classList.remove('show');
            localStorage.setItem('pwa-installed', 'true');
        });
    </script>
</body>
</html>
