@extends('layouts.default-dashboard')

@section('title', 'Pengajuan Pengembalian')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengajuan Pengembalian</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola pengajuan pengembalian kendaraan</p>
    </div>

    {{-- Tab Filter Status --}}
    <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-slate-700 pb-0">
        @php
            $tabs = [
                null          => 'Semua',
                'pending'     => 'Pending',
                'dikonfirmasi'=> 'Dikonfirmasi',
                'ditolak'     => 'Ditolak',
            ];
        @endphp
        @foreach($tabs as $statusKey => $label)
            <a href="{{ route('pengembalian.index', $statusKey ? ['status' => $statusKey] : []) }}"
               class="px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition-colors
                      {{ $currentStatus === $statusKey
                          ? 'border-primary text-primary dark:text-blue-400 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20'
                          : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-slate-500' }}">
                {{ $label }}
                @if($statusKey === 'pending')
                    @php $pendingCount = \App\Models\RiwayatPengembalian::where('status','pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full
                                     bg-red-500 text-white text-xs font-bold">
                            {{ $pendingCount }}
                        </span>
                    @endif
                @endif
            </a>
        @endforeach
    </div>

    {{-- Tabel --}}
    <div class="card">
        @if($riwayats->isEmpty())
            <div class="py-12 text-center">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                </svg>
                <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada data pengajuan pengembalian.</p>
            </div>
        @else
            <div class="table-container">
                <table class="table-base datatable">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Kendaraan</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Diajukan</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayats as $r)
                            <tr>
                                <td class="font-medium">{{ $r->peminjaman?->user?->username ?? '-' }}</td>
                                <td>
                                    <div class="font-semibold">{{ $r->peminjaman?->kendaraan?->plat_nomor ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ $r->peminjaman?->kendaraan?->merk }}
                                        {{ $r->peminjaman?->kendaraan?->model }}
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($r->peminjaman?->tanggal_pinjam)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($r->tanggal_pengajuan)->format('d/m/Y H:i') }}</td>
                                <td class="max-w-xs">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $r->catatan_pengembalian ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($r->status === 'pending')
                                        <span class="badge-yellow">Pending</span>
                                    @elseif($r->status === 'dikonfirmasi')
                                        <span class="badge-green">Dikonfirmasi</span>
                                    @else
                                        <span class="badge-red">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($r->status === 'pending')
                                        <div class="flex items-center gap-2">
                                            <form method="POST"
                                                  action="{{ route('pengembalian.konfirmasi', $r) }}"
                                                  x-data="{ loading: false }"
                                                  @submit="loading = true"
                                                  onsubmit="return confirm('Konfirmasi pengembalian ini? Kendaraan akan kembali tersedia.')">
                                                @csrf @method('PUT')
                                                <button type="submit"
                                                        class="btn-success text-xs py-1 px-3"
                                                        :disabled="loading">
                                                    Konfirmasi
                                                </button>
                                            </form>
                                            <form method="POST"
                                                  action="{{ route('pengembalian.tolak', $r) }}"
                                                  x-data="{ loading: false }"
                                                  @submit="loading = true"
                                                  onsubmit="return confirm('Tolak pengajuan pengembalian ini?')">
                                                @csrf @method('PUT')
                                                <button type="submit"
                                                        class="btn-danger text-xs py-1 px-3"
                                                        :disabled="loading">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">
                                            @if($r->tanggal_konfirmasi)
                                                {{ \Carbon\Carbon::parse($r->tanggal_konfirmasi)->format('d/m/Y H:i') }}
                                            @else
                                                &mdash;
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
