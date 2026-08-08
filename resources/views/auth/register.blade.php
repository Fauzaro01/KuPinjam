<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | KuPinjam</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function () {
            if (localStorage.getItem('darkmode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-primary rounded-2xl shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h12l2-2V9l-3-5H9"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">KuPinjam</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Buat akun baru</p>
        </div>

        {{-- Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Daftar Akun</h2>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('store') }}" class="space-y-4"
                  x-data="{ loading: false }" @submit="loading = true">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Pengguna
                    </label>
                    <input type="text" id="username" name="username"
                           value="{{ old('username') }}"
                           class="input-field @error('username') border-red-500 @enderror"
                           placeholder="Nama lengkap Anda"
                           required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Email
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           class="input-field @error('email') border-red-500 @enderror"
                           placeholder="nama@perusahaan.com"
                           required>
                </div>

                <div>
                    <label for="no_telp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nomor Telepon
                    </label>
                    <input type="text" id="no_telp" name="no_telp"
                           value="{{ old('no_telp') }}"
                           class="input-field @error('no_telp') border-red-500 @enderror"
                           placeholder="0812xxxxxxxx"
                           required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Password
                    </label>
                    <input type="password" id="password" name="password"
                           class="input-field @error('password') border-red-500 @enderror"
                           placeholder="Min. 8 karakter"
                           required>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Konfirmasi Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="input-field"
                           placeholder="Ulangi password"
                           required>
                </div>

                <button type="submit" class="w-full btn-primary py-2.5 text-sm mt-2 flex items-center justify-center gap-2"
                        :disabled="loading">
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="loading ? 'Memproses...' : 'Daftar Sekarang'">Daftar Sekarang</span>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">Masuk</a>
            </p>
        </div>
    </div>

</body>
</html>
