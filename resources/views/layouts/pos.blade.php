<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS') }} - Kasir</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        @php
            $viteManifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $cssFile = $viteManifest['resources/css/app.css']['file'] ?? '';
            $jsFile = $viteManifest['resources/js/app.js']['file'] ?? '';
        @endphp
        <link rel="stylesheet" href="/build/{{ $cssFile }}">
        <script type="module" src="/build/{{ $jsFile }}"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50 overflow-hidden">
        <!-- Top Bar -->
        <div class="h-12 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-6 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-indigo-100 hover:text-indigo-600 text-gray-700 rounded-lg transition-all font-medium text-sm" title="Kembali ke Dashboard">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm hidden sm:inline">{{ config('app.name', 'POS') }}</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-500 hidden md:flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="pos-clock"></span>
                </div>

                <div class="h-5 w-px bg-gray-200 hidden md:block"></div>

                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-xs font-semibold text-indigo-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <span class="text-sm font-medium text-gray-700 hidden sm:inline">{{ Auth::user()->name }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100" title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <main class="h-[calc(100vh-3rem)]">
            {{ $slot }}
        </main>

        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-16 right-4 z-[100] space-y-2"></div>

        <script>
            // Real-time clock
            function updateClock() {
                const el = document.getElementById('pos-clock');
                if (el) {
                    el.textContent = new Date().toLocaleString('id-ID', {
                        weekday: 'short', day: '2-digit', month: 'short', year: 'numeric',
                        hour: '2-digit', minute: '2-digit', second: '2-digit'
                    });
                }
            }
            updateClock();
            setInterval(updateClock, 1000);

            // Global toast function
            window.showToast = function(message, type = 'success') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');

                const colors = {
                    success: 'bg-green-600',
                    error: 'bg-red-600',
                    info: 'bg-blue-600',
                    warning: 'bg-amber-500'
                };

                const icons = {
                    success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
                    error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />',
                    info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
                };

                toast.className = `${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 text-sm font-medium transform translate-x-full transition-transform duration-300 min-w-[280px]`;
                toast.innerHTML = `
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icons[type]}</svg>
                    <span>${message}</span>
                `;

                container.appendChild(toast);
                requestAnimationFrame(() => toast.classList.remove('translate-x-full'));

                setTimeout(() => {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            };
        </script>

        @stack('scripts')
    </body>
</html>
