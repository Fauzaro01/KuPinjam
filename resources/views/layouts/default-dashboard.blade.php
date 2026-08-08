<!DOCTYPE html>
<html lang="id" x-data>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KuPinjam')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- DataTables CSS via CDN --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    {{-- Anti-FOUC: apply dark mode sebelum paint (harus di <head>) --}}
    <script>
        (function () {
            if (localStorage.getItem('darkmode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-100 dark:bg-slate-900 text-gray-900 dark:text-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('layouts.sidebar-dashboard')

        {{-- Main content area --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-auto">

            {{-- Top header (mobile hamburger) --}}
            <header class="sticky top-0 z-10 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700
                           px-4 py-3 flex items-center gap-4 lg:hidden">
                <button id="sidebar-toggle" type="button"
                        class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="text-lg font-semibold">@yield('title', 'KuPinjam')</span>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-6">
                <x-alert />
                @yield('content')
            </main>

            {{-- Footer --}}
            @include('layouts.footer-dashboard')
        </div>
    </div>

    {{-- DataTables JS via CDN (after jQuery) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    @stack('scripts')
</body>
</html>
