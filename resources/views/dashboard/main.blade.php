@extends('layouts.default-dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Page heading --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
            Selamat datang, <span class="font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->username }}</span>
        </p>
    </div>

    @if(Auth::user()->hasRole('administrator'))

        {{-- ===== ADMIN: Stat Cards ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- Total Kendaraan --}}
            <a href="{{ route('kendaraan.index') }}" class="card flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h12l2-2V9l-3-5H9"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Kendaraan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kendaraan->count() }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400">
                        {{ $kendaraan->where('status','tersedia')->count() }} tersedia
                    </p>
                </div>
            </a>

            {{-- Peminjaman Aktif --}}
            <a href="{{ route('peminjaman.index') }}" class="card flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 dark:bg-yellow-900/40 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Peminjaman Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $peminjamans->where('status_peminjaman','dipinjam')->count() }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        {{ $peminjamans->where('status_peminjaman','selesai')->count() }} selesai
                    </p>
                </div>
            </a>

            {{-- Pengembalian Pending --}}
            <a href="{{ route('pengembalian.index') }}" class="card flex items-center gap-4 hover:shadow-md transition-shadow relative">
                @if($pendingPengembalian > 0)
                    <span class="absolute top-3 right-3 inline-flex items-center justify-center w-5 h-5
                                 rounded-full bg-red-500 text-white text-xs font-bold">
                        {{ $pendingPengembalian }}
                    </span>
                @endif
                <div class="flex-shrink-0 w-12 h-12 bg-orange-100 dark:bg-orange-900/40 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Perlu Konfirmasi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingPengembalian }}</p>
                    <p class="text-xs text-orange-500 dark:text-orange-400">
                        {{ $pendingPengembalian > 0 ? 'Butuh perhatian' : 'Semua clear' }}
                    </p>
                </div>
            </a>

            {{-- Total User --}}
            <a href="{{ route('usermanagement.index') }}" class="card flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 w-12 h-12 bg-purple-100 dark:bg-purple-900/40 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total User</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $users->count() }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        {{ $users->where('role','karyawan')->count() }} karyawan
                    </p>
                </div>
            </a>
        </div>

        {{-- Tabel peminjaman aktif terbaru --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Peminjaman Aktif Terbaru</h2>
                <a href="{{ route('peminjaman.index') }}" class="text-sm text-primary hover:underline">Lihat semua &rarr;</a>
            </div>

            @php $aktif = $peminjamans->where('status_peminjaman', 'dipinjam')->take(5); @endphp

            @if($aktif->isEmpty())
                <div class="py-8 text-center text-gray-400 dark:text-gray-500">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                    <p class="text-sm">Tidak ada peminjaman aktif.</p>
                </div>
            @else
                <div class="table-container">
                    <table class="table-base">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Kendaraan</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aktif as $p)
                                <tr>
                                    <td class="font-medium">{{ $p->user?->username ?? '-' }}</td>
                                    <td>
                                        <div class="font-semibold">{{ $p->kendaraan?->plat_nomor ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">{{ $p->kendaraan?->merk }} {{ $p->kendaraan?->model }}</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $kembali = \Carbon\Carbon::parse($p->tanggal_kembali);
                                            $isLate  = $kembali->isPast();
                                        @endphp
                                        <span class="{{ $isLate ? 'text-red-500 font-semibold' : '' }}">
                                            {{ $kembali->format('d/m/Y') }}
                                        </span>
                                        @if($isLate)
                                            <span class="badge-red ml-1">Terlambat</span>
                                        @endif
                                    </td>
                                    <td><span class="badge-yellow">Dipinjam</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    @else

        {{-- ===== KARYAWAN VIEW ===== --}}
        @php
            $aktifSaya   = isset($myPeminjamans) ? $myPeminjamans->where('status_peminjaman','dipinjam') : collect();
            $selesaiSaya = isset($myPeminjamans) ? $myPeminjamans->where('status_peminjaman','selesai')  : collect();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <a href="{{ route('kendaraan.index') }}" class="card flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h12l2-2V9l-3-5H9"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pinjam Kendaraan</p>
                    <p class="text-sm font-medium text-primary">Lihat kendaraan tersedia</p>
                </div>
            </a>

            <div class="card flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 dark:bg-yellow-900/40 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $aktifSaya->count() }}</p>
                </div>
            </div>

            <div class="card flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $selesaiSaya->count() }}</p>
                </div>
            </div>
        </div>

        {{-- Peminjaman aktif karyawan --}}
        @if($aktifSaya->isNotEmpty())
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Peminjaman Aktif Saya</h2>
                    <a href="{{ route('peminjaman.index') }}" class="text-sm text-primary hover:underline">Lihat semua &rarr;</a>
                </div>
                <div class="table-container">
                    <table class="table-base">
                        <thead>
                            <tr>
                                <th>Kendaraan</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status Pengembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aktifSaya as $p)
                                <tr>
                                    <td>
                                        <div class="font-semibold">{{ $p->kendaraan?->plat_nomor ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">{{ $p->kendaraan?->merk }} {{ $p->kendaraan?->model }}</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($p->riwayatPengembalian?->status === 'pending')
                                            <span class="badge-blue">Menunggu Konfirmasi</span>
                                        @else
                                            <span class="badge-gray">Belum diajukan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    @endif

</div>
@endsection
