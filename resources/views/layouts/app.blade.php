<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @php
            $viteManifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $cssFile = $viteManifest['resources/css/app.css']['file'] ?? '';
            $jsFile = $viteManifest['resources/js/app.js']['file'] ?? '';
        @endphp
        <link rel="stylesheet" href="/build/{{ $cssFile }}">
        <script type="module" src="/build/{{ $jsFile }}"></script>
    </head>
    <body class="font-sans antialiased">
        <noscript>
            <div class="p-8 text-center text-gray-600 bg-gray-100 min-h-screen">
                <p class="text-lg font-semibold">Aplikasi membutuhkan JavaScript</p>
                <p class="mt-2 text-sm">Mohon aktifkan JavaScript di browser Anda untuk menggunakan aplikasi ini.</p>
            </div>
        </noscript>
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-16 right-4 z-[100] space-y-2"></div>

        <script>
            window.showToast = function(message, type = 'success') {
                const container = document.getElementById('toast-container');
                if (!container) return;
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
                const svgEl = document.createElement('div');
                svgEl.innerHTML = `<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icons[type]}</svg>`;
                toast.appendChild(svgEl.firstElementChild);
                const spanEl = document.createElement('span');
                spanEl.textContent = message;
                toast.appendChild(spanEl);
                const closeBtn = document.createElement('button');
                closeBtn.innerHTML = '<svg class="w-4 h-4 ml-2 opacity-70 hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                closeBtn.onclick = function() {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                };
                toast.appendChild(closeBtn);
                container.appendChild(toast);
                requestAnimationFrame(() => toast.classList.remove('translate-x-full'));
                const duration = type === 'error' ? 5000 : 3000;
                let timer = setTimeout(() => {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, duration);
                toast.addEventListener('mouseenter', () => clearTimeout(timer));
                toast.addEventListener('mouseleave', () => {
                    timer = setTimeout(() => {
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => toast.remove(), 300);
                    }, 2000);
                });
            };
        </script>

        @stack('scripts')
    </body>
</html>
