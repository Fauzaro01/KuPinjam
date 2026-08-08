@extends('layouts.default-dashboard')

@section('title', 'Edit Jadwal Perawatan')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div>
        <a href="{{ route('perawatan.index') }}" class="text-sm text-primary hover:underline">&larr; Kembali ke daftar</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Edit Jadwal Perawatan</h1>
    </div>

    <div class="card">
        <div class="mb-4 p-3 bg-gray-50 dark:bg-slate-700/30 rounded-xl">
            <span class="text-xs text-gray-400">Kendaraan yang Dirawat</span>
            <p class="font-bold text-gray-900 dark:text-white mt-0.5">
                {{ $perawatan->kendaraan?->plat_nomor }} — {{ $perawatan->kendaraan?->merk }} {{ $perawatan->kendaraan?->model }}
            </p>
        </div>

        <form method="POST" action="{{ route('perawatan.update', $perawatan->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="jenis_perawatan" class="label-base">Jenis Perawatan</label>
                <input type="text" id="jenis_perawatan" name="jenis_perawatan" value="{{ old('jenis_perawatan', $perawatan->jenis_perawatan) }}" class="input-base mt-1" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_mulai" class="label-base">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $perawatan->tanggal_mulai->format('Y-m-d')) }}" class="input-base mt-1" required>
                </div>
                <div>
                    <label for="estimasi_selesai" class="label-base">Estimasi Selesai (Opsional)</label>
                    <input type="date" id="estimasi_selesai" name="estimasi_selesai" value="{{ old('estimasi_selesai', $perawatan->estimasi_selesai ? $perawatan->estimasi_selesai->format('Y-m-d') : '') }}" class="input-base mt-1">
                </div>
            </div>

            <div>
                <label for="catatan" class="label-base">Catatan Perawatan / Keluhan (Opsional)</label>
                <textarea id="catatan" name="catatan" rows="4" class="input-base mt-1">{{ old('catatan', $perawatan->catatan) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('perawatan.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
