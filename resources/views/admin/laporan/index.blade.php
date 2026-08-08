@extends('layouts.default-dashboard')

@section('title', 'Laporan Peminjaman')

@section('content')
<div class="space-y-6 print:space-y-4 print:p-0">

    {{-- Header / Judul (Disembunyikan saat cetak karena kita pakai Kop Surat formal di bawah) --}}
    <div class="flex items-center justify-between flex-wrap gap-3 print:hidden">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Peminjaman</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Buat, filter, dan cetak laporan peminjaman kendaraan</p>
        </div>
        <button
            onclick="window.print()"
            type="button"
            class="btn-primary flex items-center gap-2 text-sm shadow hover:shadow-lg transition-all"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2zm0 0l-9-9"/>
            </svg>
            Cetak Laporan / PDF
        </button>
    </div>

    {{-- KOP SURAT FORMAL (Hanya tampil saat dicetak / print) --}}
    <div class="hidden print:block text-black">
        <div class="text-center border-b-4 border-double border-gray-800 pb-4 mb-6">
            <h2 class="text-2xl font-bold uppercase tracking-wide">PT. KUPINJAM INDONESIA</h2>
            <p class="text-xs text-gray-600 mt-1">
                Gedung Utama KuPinjam Lt. 5, Jl. Jendral Sudirman No. 42, Jakarta Pusat, DKI Jakarta
            </p>
            <p class="text-xs text-gray-500 mt-0.5">Telp: (021) 555-0199 | Email: info@kupinjam.co.id</p>
        </div>

        <div class="text-center mb-6">
            <h3 class="text-lg font-bold uppercase underline">LAPORAN PEMINJAMAN KENDARAAN</h3>
            <p class="text-sm text-gray-700 mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            </p>
        </div>
    </div>

    {{-- Form Filter Tanggal (Disembunyikan saat cetak) --}}
    <div class="card print:hidden">
        <form method="GET" action="{{ route('admin.laporan') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-field" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-field" required>
            </div>
            <div>
                <button type="submit" class="btn-primary w-full py-2.5">
                    Filter Laporan
                </button>
            </div>
        </form>
    </div>

    {{-- Kartu Statistik Ringkas (Tampil saat cetak maupun biasa) --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="card p-4 border border-gray-100 dark:border-slate-700 text-center bg-gray-50 dark:bg-slate-800/40">
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">Total Peminjaman</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPeminjaman }}</p>
        </div>
        <div class="card p-4 border border-gray-100 dark:border-slate-700 text-center bg-green-50/50 dark:bg-green-950/10">
            <p class="text-xs sm:text-sm text-green-700 dark:text-green-400 font-medium">Selesai (Kembali)</p>
            <p class="text-xl sm:text-2xl font-bold text-green-700 dark:text-green-400 mt-1">{{ $totalSelesai }}</p>
        </div>
        <div class="card p-4 border border-gray-100 dark:border-slate-700 text-center bg-yellow-50/50 dark:bg-yellow-950/10">
            <p class="text-xs sm:text-sm text-yellow-700 dark:text-yellow-400 font-medium">Sedang Dipinjam</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-700 dark:text-yellow-400 mt-1">{{ $totalDipinjam }}</p>
        </div>
    </div>

    {{-- Tabel Utama Laporan --}}
    <div class="card print:border-none print:shadow-none print:p-0">
        <div class="table-container">
            <table class="table-base w-full print:text-black">
                <thead>
                    <tr>
                        <th style="width:10%">ID</th>
                        <th style="width:20%">Karyawan</th>
                        <th style="width:20%">Kendaraan</th>
                        <th style="width:15%">Tgl Pinjam</th>
                        <th style="width:15%">Tgl Kembali</th>
                        <th style="width:10%">Status</th>
                        <th>Tujuan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $p)
                        <tr class="print:border-b print:border-gray-300">
                            <td class="font-mono text-xs">#{{ $p->id }}</td>
                            <td>
                                <div class="font-medium text-gray-900 dark:text-white print:text-black">{{ $p->user?->username ?? '-' }}</div>
                                <div class="text-xs text-gray-400 print:text-gray-600">{{ $p->user?->email }}</div>
                            </td>
                            <td>
                                <div class="font-semibold text-gray-900 dark:text-white print:text-black">{{ $p->kendaraan?->plat_nomor ?? '-' }}</div>
                                <div class="text-xs text-gray-400 print:text-gray-600">{{ $p->kendaraan?->merk }} {{ $p->kendaraan?->model }}</div>
                            </td>
                            <td class="whitespace-nowrap text-sm">
                                {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}
                            </td>
                            <td class="whitespace-nowrap text-sm">
                                {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}
                            </td>
                            <td>
                                @if($p->status_peminjaman === 'selesai')
                                    <span class="badge-green print:text-black">Selesai</span>
                                @else
                                    @php
                                        $isOverdue = \Carbon\Carbon::parse($p->tanggal_kembali)->isPast();
                                    @endphp
                                    @if($isOverdue)
                                        <span class="badge-red print:text-black">Terlambat</span>
                                    @else
                                        <span class="badge-yellow print:text-black">Dipinjam</span>
                                    @endif
                                @endif
                            </td>
                            <td class="text-xs">
                                <div class="font-medium">{{ $p->tujuan }}</div>
                                @if($p->keterangan)
                                    <div class="text-gray-400 print:text-gray-600 mt-0.5 italic">"{{ $p->keterangan }}"</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-400">
                                Tidak ada data peminjaman dalam rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Penanda Tangan Laporan (Hanya tampil saat dicetak) --}}
    <div class="hidden print:block text-black mt-12">
        <div class="flex justify-end">
            <div class="text-center w-64">
                <p class="text-sm">Jakarta, {{ now()->format('d F Y') }}</p>
                <p class="text-sm font-semibold mt-1">Administrator Sistem</p>
                <div class="h-20"></div>
                <p class="text-sm font-bold underline">{{ Auth::user()->username }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
    @media print {
        /* Sembunyikan sidebar, top header default-dashboard, layout footer, dan tombol print */
        #sidebar, #sidebar-overlay, header, footer, .print\:hidden, .btn-primary, .card form {
            display: none !important;
        }
        
        /* Maksimalkan lebar konten */
        body {
            background-color: white !important;
            color: black !important;
        }
        
        main {
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: 0 !important;
        }

        .table-base {
            border-collapse: collapse !important;
        }

        .table-base th {
            background-color: #f3f4f6 !important;
            color: black !important;
            border-bottom: 2px solid #d1d5db !important;
        }

        .table-base td, .table-base th {
            border: 1px solid #e5e7eb !important;
            padding: 8px !important;
        }
    }
</style>
@endpush
@endsection
