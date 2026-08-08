@extends('layouts.default-dashboard')

@section('title', 'Peminjaman')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Peminjaman</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                {{ Auth::user()->hasRole('administrator') ? 'Semua data peminjaman' : 'Riwayat peminjaman Anda' }}
            </p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            @if(Auth::user()->hasRole('administrator'))
                <form method="GET" action="{{ route('peminjaman.export-csv') }}" class="flex items-center gap-2 flex-wrap">
                    <input type="date" name="start_date" class="input-field py-1 px-2 text-xs" style="max-width: 145px;" placeholder="Dari Tanggal">
                    <span class="text-xs text-gray-500">s/d</span>
                    <input type="date" name="end_date" class="input-field py-1 px-2 text-xs" style="max-width: 145px;" placeholder="Sampai Tanggal">
                    <button type="submit" class="btn-secondary flex items-center gap-2 text-xs py-1 px-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export CSV
                    </button>
                </form>
                <a href="{{ route('peminjaman.create') }}" class="btn-primary flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Peminjaman
                </a>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="table-base datatable w-full" data-server-paginated="true">
                <thead>
                    <tr>
                        @if(Auth::user()->hasRole('administrator'))
                            <th style="width:12%">Karyawan</th>
                        @endif
                        <th style="width:15%">Kendaraan</th>
                        <th style="width:9%">Tgl Pinjam</th>
                        <th style="width:9%">Tgl Kembali</th>
                        <th>Tujuan</th>
                        <th style="width:10%">Status Peminjaman</th>
                        <th style="width:14%">Status Pengembalian</th>
                        <th style="width:12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $p)
                        <tr>
                            @if(Auth::user()->hasRole('administrator'))
                                <td class="font-medium">{{ $p->user?->username ?? '-' }}</td>
                            @endif
                            <td>
                                <div class="font-semibold">{{ $p->kendaraan?->plat_nomor ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $p->kendaraan?->merk }} {{ $p->kendaraan?->model }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td>
                                @php $kembali = \Carbon\Carbon::parse($p->tanggal_kembali); @endphp
                                <span class="{{ ($kembali->isPast() && $p->status_peminjaman === 'dipinjam') ? 'text-red-500 font-semibold' : '' }}">
                                    {{ $kembali->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $p->tujuan }}</div>
                                @if($p->dokumens->isNotEmpty())
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach($p->dokumens as $dok)
                                            <a href="{{ Storage::url($dok->file_path) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-[10px] bg-slate-100 dark:bg-slate-700 hover:bg-primary/10 text-gray-600 dark:text-gray-300 hover:text-primary px-1.5 py-0.5 rounded transition-colors"
                                               title="{{ $dok->file_name }}">
                                                📎 {{ \Illuminate\Support\Str::limit($dok->file_name, 15) }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if(Auth::user()->hasRole('administrator') && $p->admin_notes)
                                    <div class="mt-1.5 p-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-200/50 dark:border-amber-900/30 text-[11px] text-amber-800 dark:text-amber-400">
                                        <span class="font-semibold uppercase tracking-wider text-[9px] block text-amber-600 dark:text-amber-500">Catatan Internal:</span>
                                        {{ $p->admin_notes }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($p->is_overdue)
                                    <span class="badge-red">Terlambat</span>
                                @elseif($p->status_peminjaman === 'dipinjam')
                                    <span class="badge-yellow">Dipinjam</span>
                                @else
                                    <span class="badge-green">Selesai</span>
                                @endif
                            </td>

                            {{-- ── Kolom Status Pengembalian (Task 34) ── --}}
                            <td class="whitespace-nowrap">
                                @if($p->riwayatPengembalian?->status === 'pending')
                                    <span class="badge-yellow">Menunggu Konfirmasi</span>
                                @elseif($p->riwayatPengembalian?->status === 'dikonfirmasi')
                                    <span class="badge-green">Selesai</span>
                                @elseif($p->riwayatPengembalian?->status === 'ditolak')
                                    <div>
                                        <span class="badge-red">Ditolak</span>
                                        @if(Auth::user()->hasRole('karyawan') && $p->user_id === Auth::id() && $p->status_peminjaman === 'dipinjam')
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Bisa ajukan ulang</p>
                                        @endif
                                    </div>
                                @elseif($p->status_peminjaman === 'dipinjam')
                                    @can('ajukanPengembalian', $p)
                                        {{-- Tombol ajukan (Task 34.2) --}}
                                        <form method="POST"
                                              action="{{ route('pengembalian.ajukan', $p) }}"
                                              x-data="{ loading: false }"
                                              @submit="loading = true">
                                            @csrf
                                            <button type="submit"
                                                    class="btn-success text-xs py-1 px-3"
                                                    :disabled="loading">
                                                <svg x-show="loading" class="inline w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                                </svg>
                                                <span x-text="loading ? 'Memproses...' : 'Kembalikan'">Kembalikan</span>
                                            </button>
                                        </form>
                                    @endcan
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">&mdash;</span>
                                  @endif
                            </td>

                            {{-- ── Kolom Aksi ── --}}
                            <td class="whitespace-nowrap">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <a href="{{ route('peminjaman.surat-jalan', $p) }}" target="_blank"
                                       class="btn-primary text-xs py-1 px-2.5 flex items-center gap-1 font-semibold">
                                        🖨️ Cetak
                                    </a>
                                    @can('update', $p)
                                        <a href="{{ route('peminjaman.edit', $p) }}"
                                           class="btn-secondary text-xs py-1 px-3">Edit</a>
                                        <form method="POST" action="{{ route('peminjaman.destroy', $p) }}"
                                              onsubmit="return confirm('Hapus peminjaman ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger text-xs py-1 px-3">Hapus</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-400">Belum ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($peminjamans->hasPages())
            <div class="mt-4">
                {{ $peminjamans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
