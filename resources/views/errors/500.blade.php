<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Kesalahan Server | KuPinjam</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>(function(){if(localStorage.getItem('darkmode')==='true')document.documentElement.classList.add('dark')})();</script>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-slate-900 font-sans flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        {{-- Ilustrasi --}}
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-yellow-100 dark:bg-yellow-900/30 rounded-full mb-4">
                <svg class="w-12 h-12 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="text-7xl font-extrabold text-gray-200 dark:text-slate-700 select-none">500</div>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Kesalahan Server</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-8">
            Terjadi kesalahan pada server. Tim teknis kami sudah diberitahu.
            Coba lagi dalam beberapa saat.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url()->previous() }}" class="btn-primary">
                &larr; Coba Lagi
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    Dashboard
                </a>
            @else
                <a href="{{ route('home') }}" class="btn-secondary">
                    Beranda
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
