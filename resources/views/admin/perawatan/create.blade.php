@extends('layouts.default-dashboard')

@section('title', 'Jadwal Perawatan Baru')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div>
        <a href="{{ route('perawatan.index') }}" class="text-sm text-primary hover:underline">&larr; Kembali ke daftar</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Jadwalkan Perawatan</h1>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('perawatan.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="kendaraan_id" class="label-base">Pilih Kendaraan</label>
                <select id="kendaraan_id" name="kendaraan_id" class="input-base mt-1" required>
                    <option value="" disabled selected>-- Pilih Kendaraan --</option>
                    @foreach($kendaraans as $k)
                        @if($k->status === 'tersedia')
                            <option value="{{ $k->id }}">{{ $k->plat_nomor }} — {{ $k->merk }} {{ $k->model }}</option>
                        @else
                            <option value="{{ $k->id }}" disabled>{{ $k->plat_nomor }} — {{ $k->merk }} {{ $k->model }} ({{ ucfirst($k->status) }})</option>
                        @endif
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Hanya kendaraan dengan status 'Tersedia' yang dapat dijadwalkan perawatan.</p>
            </div>

            <div>
                <label for="jenis_perawatan" class="label-base">Jenis Perawatan</label>
                <input type="text" id="jenis_perawatan" name="jenis_perawatan" placeholder="Contoh: Servis Rutin 10.000 KM, Ganti Aki, Perbaikan Rem" class="input-base mt-1" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_mulai" class="label-base">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ date('Y-m-d') }}" class="input-base mt-1" required>
                </div>
                <div>
                    <label for="estimasi_selesai" class="label-base">Estimasi Selesai (Opsional)</label>
                    <input type="date" id="estimasi_selesai" name="estimasi_selesai" class="input-base mt-1">
                </div>
            </div>

            <div>
                <label for="catatan" class="label-base">Catatan Perawatan / Keluhan (Opsional)</label>
                <textarea id="catatan" name="catatan" rows="4" placeholder="Detail kendala atau item yang perlu diperbaiki..." class="input-base mt-1"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('perawatan.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Mulai &amp; Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
