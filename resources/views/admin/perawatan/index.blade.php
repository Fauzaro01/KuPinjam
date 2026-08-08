@extends('layouts.default-dashboard')

@section('title', 'Jadwal Perawatan Kendaraan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Perawatan Kendaraan</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola dan jadwalkan perawatan berkala atau perbaikan kendaraan perusahaan.</p>
        </div>
        <a href="{{ route('perawatan.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Jadwalkan Perawatan
        </a>
    </div>

    <div class="card">
        @if($perawatans->isEmpty())
            <div class="py-12 text-center text-gray-400 dark:text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-base">Belum ada jadwal perawatan kendaraan.</p>
            </div>
        @else
            <div class="table-container">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Kendaraan</th>
                            <th>Jenis Perawatan</th>
                            <th>Tanggal Mulai</th>
                            <th>Estimasi Selesai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perawatans as $p)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $p->kendaraan?->plat_nomor ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $p->kendaraan?->merk }} {{ $p->kendaraan?->model }}</div>
                                </td>
                                <td class="font-medium text-gray-900 dark:text-white">{{ $p->jenis_perawatan }}</td>
                                <td>{{ $p->tanggal_mulai->format('d/m/Y') }}</td>
                                <td>{{ $p->estimasi_selesai ? $p->estimasi_selesai->format('d/m/Y') : '-' }}</td>
                                <td>{{ $p->tanggal_selesai ? $p->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($p->status === 'selesai')
                                        <span class="badge-green">Selesai</span>
                                    @elseif($p->status === 'berlangsung')
                                        <span class="badge-blue">Berlangsung</span>
                                    @else
                                        <span class="badge-yellow">Dijadwalkan</span>
                                    @endif
                                </td>
                                <td class="max-w-xs truncate text-xs" title="{{ $p->catatan }}">{{ $p->catatan ?? '-' }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        @if($p->status !== 'selesai')
                                            <form method="POST" action="{{ route('perawatan.selesai', $p->id) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn-success py-1 px-2.5 text-xs flex items-center gap-1">
                                                    ✓ Selesai
                                                </button>
                                            </form>
                                            <a href="{{ route('perawatan.edit', $p->id) }}" class="btn-secondary py-1 px-2.5 text-xs">
                                                Edit
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('perawatan.destroy', $p->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal perawatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger py-1 px-2.5 text-xs">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $perawatans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
