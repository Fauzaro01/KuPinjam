@extends('layouts.default-dashboard')

@section('title', 'Edit Peminjaman')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('peminjaman.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Peminjaman</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-0.5">
                {{ $peminjaman->user?->username }} — {{ $peminjaman->kendaraan?->plat_nomor }}
            </p>
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

        <form method="POST" action="{{ route('peminjaman.update', $peminjaman) }}" class="space-y-5"
              x-data="{ loading: false }" @submit="loading = true">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Karyawan</label>
                <select name="user_id" class="input-field @error('user_id') border-red-500 @enderror" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', $peminjaman->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->username }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kendaraan</label>
                <select name="kendaraan_id" class="input-field @error('kendaraan_id') border-red-500 @enderror" required>
                    @foreach($kendaraans as $k)
                        <option value="{{ $k->id }}"
                            {{ old('kendaraan_id', $peminjaman->kendaraan_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->plat_nomor }} — {{ $k->merk }} {{ $k->model }}
                            @if($k->id === $peminjaman->kendaraan_id) (Saat ini) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam"
                           value="{{ old('tanggal_pinjam', \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('Y-m-d')) }}"
                           class="input-field @error('tanggal_pinjam') border-red-500 @enderror" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali"
                           value="{{ old('tanggal_kembali', \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('Y-m-d')) }}"
                           class="input-field @error('tanggal_kembali') border-red-500 @enderror" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tujuan</label>
                <input type="text" name="tujuan"
                       value="{{ old('tujuan', $peminjaman->tujuan) }}"
                       class="input-field @error('tujuan') border-red-500 @enderror" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Keterangan <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="keterangan" rows="3"
                          class="input-field @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
            </div>

            @if(Auth::user()->hasRole('administrator'))
                <div>
                    <label class="block text-sm font-medium text-amber-600 dark:text-amber-400 mb-1.5 font-semibold">
                        Catatan Internal Admin (Tidak Terlihat oleh Karyawan)
                    </label>
                    <textarea name="admin_notes" rows="3"
                              class="input-field border-amber-300 focus:border-amber-500 @error('admin_notes') border-red-500 @enderror"
                              placeholder="Tambahkan catatan internal khusus admin di sini...">{{ old('admin_notes', $peminjaman->admin_notes) }}</textarea>
                </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex items-center gap-2" :disabled="loading">
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="loading ? 'Menyimpan...' : 'Perbarui Peminjaman'">Perbarui Peminjaman</span>
                </button>
                <a href="{{ route('peminjaman.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
