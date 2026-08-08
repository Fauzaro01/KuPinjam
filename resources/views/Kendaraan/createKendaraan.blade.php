@extends('layouts.default-dashboard')

@section('title', 'Tambah Kendaraan')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('kendaraan.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Kendaraan</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-0.5">Daftarkan kendaraan baru ke sistem</p>
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

        <form method="POST" action="{{ route('kendaraan.store') }}" class="space-y-5"
              x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Plat Nomor</label>
                    <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}"
                           class="input-field @error('plat_nomor') border-red-500 @enderror"
                           placeholder="B 1234 XX" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Merk</label>
                    <input type="text" name="merk" value="{{ old('merk') }}"
                           class="input-field @error('merk') border-red-500 @enderror"
                           placeholder="Toyota, Honda, dll" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Model</label>
                    <input type="text" name="model" value="{{ old('model') }}"
                           class="input-field @error('model') border-red-500 @enderror"
                           placeholder="Avanza, Vario, dll" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tahun</label>
                    <input type="number" name="tahun" value="{{ old('tahun') }}"
                           class="input-field @error('tahun') border-red-500 @enderror"
                           placeholder="{{ date('Y') }}" min="1900" max="{{ date('Y') }}" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jenis Kendaraan</label>
                    <select name="jenis_kendaraan" class="input-field @error('jenis_kendaraan') border-red-500 @enderror" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="mobil" {{ old('jenis_kendaraan') === 'mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="motor" {{ old('jenis_kendaraan') === 'motor' ? 'selected' : '' }}>Motor</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex items-center gap-2" :disabled="loading">
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Kendaraan'">Simpan Kendaraan</span>
                </button>
                <a href="{{ route('kendaraan.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
