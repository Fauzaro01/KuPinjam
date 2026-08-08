@extends('layouts.default-dashboard')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('usermanagement.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-0.5">{{ $user->username }}</p>
        </div>
    </div>

    <div class="card">
        @if($errors->any())
            <div class="mb-5 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg">
                @foreach($errors->all() as $error)
                    <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('usermanagement.update', $user->id) }}" class="space-y-5">
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
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="input-field @error('email') border-red-500 @enderror" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nomor Telepon</label>
                <input type="text" name="no_telp"
                       value="{{ old('no_telp', $user->no_telp) }}"
                       class="input-field @error('no_telp') border-red-500 @enderror" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                </label>
                <input type="password" name="password"
                       class="input-field @error('password') border-red-500 @enderror"
                       placeholder="Min. 8 karakter">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Role</label>
                <select name="role" class="input-field @error('role') border-red-500 @enderror" required>
                    <option value="karyawan" {{ old('role', $user->role) === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                    <option value="administrator" {{ old('role', $user->role) === 'administrator' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Perbarui User</button>
                <a href="{{ route('usermanagement.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
