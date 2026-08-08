<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak | KuPinjam</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>(function(){if(localStorage.getItem('darkmode')==='true')document.documentElement.classList.add('dark')})();</script>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-slate-900 font-sans flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        {{-- Ilustrasi --}}
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 dark:bg-red-900/30 rounded-full mb-4">
                <svg class="w-12 h-12 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div class="text-7xl font-extrabold text-gray-200 dark:text-slate-700 select-none">403</div>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Akses Ditolak</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-8">
            Kamu tidak memiliki izin untuk mengakses halaman ini.
            Hubungi administrator jika kamu yakin ini adalah kesalahan.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">
                    Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary">
                    Masuk
                </a>
            @endauth
            <a href="{{ url()->previous() }}" class="btn-secondary">
                &larr; Halaman Sebelumnya
            </a>
        </div>
    </div>
</body>
</html>
