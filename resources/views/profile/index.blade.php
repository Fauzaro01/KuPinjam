@extends('layouts.default-dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profil Saya</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Informasi akun dan foto profil Anda</p>
    </div>

    {{-- Avatar card --}}
    <div class="card flex items-center gap-6">
        <div class="flex-shrink-0">
            @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}"
                     alt="Avatar"
                     class="w-20 h-20 rounded-full object-cover border-4 border-white dark:border-slate-700 shadow">
            @else
                <div class="w-20 h-20 rounded-full bg-primary flex items-center justify-center shadow">
                    <span class="text-white text-3xl font-bold">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </span>
                </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->username }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 capitalize mt-0.5">{{ $user->role }}</p>
        </div>
        <div>
            <form method="POST" action="{{ route('profile.avatar') }}"
                  enctype="multipart/form-data" id="avatar-form">
                @csrf
                <label class="btn-secondary text-sm cursor-pointer">
                    Ganti Foto
                    <input type="file" name="avatar" accept="image/*" class="hidden"
                           onchange="document.getElementById('avatar-form').submit()">
                </label>
            </form>
        </div>
    </div>

    {{-- Edit profil --}}
    <div class="card">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-5">Edit Profil</h2>

        @if($errors->any())
            <div class="mb-5 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg">
                @foreach($errors->all() as $error)
                    <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5"
              x-data="{ loading: false }" @submit="loading = true">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Pengguna</label>
                <input type="text" name="username"
                       value="{{ old('username', $user->username) }}"
                       class="input-field @error('username') border-red-500 @enderror" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                <input type="email" value="{{ $user->email }}"
                       class="input-field bg-gray-50 dark:bg-slate-700/50 cursor-not-allowed"
                       disabled>
                <p class="mt-1 text-xs text-gray-400">Email tidak dapat diubah.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nomor Telepon</label>
                <input type="text" name="no_telp"
                       value="{{ old('no_telp', $user->no_telp) }}"
                       class="input-field @error('no_telp') border-red-500 @enderror" required>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex items-center gap-2" :disabled="loading">
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Link ke keamanan akun --}}
    <div class="card flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Keamanan Akun</p>
            <p class="text-xs text-gray-400 mt-0.5">Perbarui password akun Anda</p>
        </div>
        <a href="{{ route('accountSecurity') }}" class="btn-secondary text-sm">Ubah Password</a>
    </div>
</div>
@endsection
