@extends('layouts.default-dashboard')

@section('title', 'Keamanan Akun')

@section('content')
<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Keamanan Akun</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Perbarui password akun Anda</p>
    </div>

    {{-- Flash error dari session key 'error' (bukan 'eror') --}}
    @if(session('error'))
        <div class="mb-4 flex items-start gap-3 border rounded-lg px-4 py-3
                    bg-red-100 text-red-800 border-red-300 dark:bg-red-900/40 dark:text-red-200 dark:border-red-700">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="card">
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg">
                @foreach($errors->all() as $error)
                    <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('changePassword') }}" class="space-y-5">
            @csrf

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Password Saat Ini
                </label>
                <input type="password" id="current_password" name="current_password"
                       class="input-field @error('current_password') border-red-500 @enderror"
                       placeholder="••••••••"
                       required>
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Password Baru
                </label>
                <input type="password" id="new_password" name="new_password"
                       class="input-field @error('new_password') border-red-500 @enderror"
                       placeholder="Min. 8 karakter"
                       required>
            </div>

            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Konfirmasi Password Baru
                </label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                       class="input-field"
                       placeholder="Ulangi password baru"
                       required>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    Perbarui Password
                </button>
                <a href="{{ route('dashboard') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
