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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalKendaraan }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400">{{ $tersediaKendaraan }} tersedia</p>
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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $aktivPeminjaman }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $selesaiPeminjaman }} selesai</p>
                </div>
            </a>

            {{-- Terlambat --}}
            <a href="{{ route('peminjaman.index') }}" class="card flex items-center gap-4 hover:shadow-md transition-shadow relative">
                @if($terlambatCount > 0)
                    <span class="absolute top-3 right-3 inline-flex items-center justify-center w-5 h-5
                                 rounded-full bg-red-500 text-white text-xs font-bold">
                        {{ $terlambatCount }}
                    </span>
                @endif
                <div class="flex-shrink-0 w-12 h-12 {{ $terlambatCount > 0 ? 'bg-red-100 dark:bg-red-900/40' : 'bg-gray-100 dark:bg-slate-700/40' }} rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 {{ $terlambatCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Terlambat</p>
                    <p class="text-2xl font-bold {{ $terlambatCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                        {{ $terlambatCount }}
                    </p>
                    <p class="text-xs {{ $terlambatCount > 0 ? 'text-red-500' : 'text-gray-400 dark:text-gray-500' }}">
                        {{ $terlambatCount > 0 ? 'Perlu ditindaklanjuti' : 'Semua tepat waktu' }}
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
        </div>

        {{-- Baris ke-2: Kendaraan breakdown + User --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="card flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/40 rounded-lg flex items-center justify-center">
                    <span class="text-green-700 dark:text-green-400 font-bold text-sm">✓</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Kendaraan Tersedia</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $tersediaKendaraan }}</p>
                </div>
            </div>
            <div class="card flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 dark:bg-yellow-900/40 rounded-lg flex items-center justify-center">
                    <span class="text-yellow-700 dark:text-yellow-400 font-bold text-sm">⏱</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Sedang Dipinjam</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $dipinjamKendaraan }}</p>
                </div>
            </div>
            <div class="card flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-gray-100 dark:bg-slate-700/40 rounded-lg flex items-center justify-center">
                    <span class="text-gray-600 dark:text-gray-400 font-bold text-sm">🔧</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Dalam Perawatan</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $perawatanKendaraan }}</p>
                </div>
            </div>
        </div>

        {{-- Section Grafik Analitik --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- Grafik Tren Peminjaman --}}
            <div class="card lg:col-span-2">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Tren Peminjaman (6 Bulan Terakhir)</h3>
                <div class="h-64">
                    <canvas id="chart-trend"></canvas>
                </div>
            </div>
            {{-- Grafik Distribusi Status --}}
            <div class="card">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Distribusi Status Kendaraan</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="chart-status"></canvas>
                </div>
            </div>
        </div>

        {{-- Tabel peminjaman aktif terbaru --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Peminjaman Aktif Terbaru</h2>
                <a href="{{ route('peminjaman.index') }}" class="text-sm text-primary hover:underline">Lihat semua &rarr;</a>
            </div>

            @if($peminjamanAktif->isEmpty())
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
                            @foreach($peminjamanAktif as $p)
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
                                    <td><span class="{{ $isLate ? 'badge-red' : 'badge-yellow' }}">{{ $isLate ? 'Terlambat' : 'Dipinjam' }}</span></td>
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
            $overdueCount = $myOverdueCount ?? 0;
        @endphp

        {{-- Alert jika ada peminjaman terlambat --}}
        @if($overdueCount > 0)
            <div class="flex items-start gap-3 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-xl">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="font-semibold text-red-800 dark:text-red-200">
                        Anda memiliki {{ $overdueCount }} peminjaman yang terlambat dikembalikan!
                    </p>
                    <p class="text-sm text-red-600 dark:text-red-300 mt-0.5">
                        Segera ajukan pengembalian atau hubungi admin.
                        <a href="{{ route('peminjaman.index') }}" class="underline font-medium">Lihat detail →</a>
                    </p>
                </div>
            </div>
        @endif

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

        {{-- Quick Actions --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <a href="{{ route('kendaraan.index') }}"
                   class="group flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-slate-700 hover:border-primary dark:hover:border-primary hover:bg-primary/5 dark:hover:bg-primary/10 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 group-hover:bg-primary flex items-center justify-center transition-colors flex-shrink-0">
                        <svg class="w-5 h-5 text-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-primary">Pinjam Kendaraan</p>
                        <p class="text-xs text-gray-400">Ajukan peminjaman baru</p>
                    </div>
                </a>

                <a href="{{ route('peminjaman.index') }}"
                   class="group flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-slate-700 hover:border-indigo-400 dark:hover:border-indigo-500 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 group-hover:bg-indigo-500 flex items-center justify-center transition-colors flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Riwayat Peminjaman</p>
                        <p class="text-xs text-gray-400">Lihat semua peminjaman saya</p>
                    </div>
                </a>

                @if($aktifSaya->isNotEmpty())
                    <a href="{{ route('peminjaman.index') }}"
                       class="group flex items-center gap-3 p-3 rounded-xl border border-orange-200 dark:border-orange-800/50 bg-orange-50/50 dark:bg-orange-900/10 hover:border-orange-400 hover:bg-orange-100/70 dark:hover:bg-orange-900/20 transition-all">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/40 group-hover:bg-orange-500 flex items-center justify-center transition-colors flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-orange-800 dark:text-orange-300 group-hover:text-orange-700">Ajukan Pengembalian</p>
                            <p class="text-xs text-orange-400">{{ $aktifSaya->count() }} kendaraan aktif</p>
                        </div>
                    </a>
                @else
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-slate-700/50 bg-gray-50/50 dark:bg-slate-800/20 opacity-50 cursor-not-allowed">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Ajukan Pengembalian</p>
                            <p class="text-xs text-gray-400">Tidak ada kendaraan aktif</p>
                        </div>
                    </div>
                @endif
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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aktifSaya as $p)
                                @php
                                    $kembali = \Carbon\Carbon::parse($p->tanggal_kembali);
                                    $isLate  = $kembali->isPast();
                                @endphp
                                <tr class="{{ $isLate ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">
                                    <td>
                                        <div class="font-semibold">{{ $p->kendaraan?->plat_nomor ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">{{ $p->kendaraan?->merk }} {{ $p->kendaraan?->model }}</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="{{ $isLate ? 'text-red-500 font-semibold' : '' }}">
                                            {{ $kembali->format('d/m/Y') }}
                                        </span>
                                        @if($isLate)
                                            <span class="badge-red ml-1">Terlambat</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($isLate)
                                            <span class="badge-red">Terlambat</span>
                                        @elseif($p->riwayatPengembalian?->status === 'pending')
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

@if(Auth::user()->hasRole('administrator'))
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const response = await fetch("{{ route('admin.analytics.data') }}");
            const data = await response.json();

            // 1. Line Chart: Tren Peminjaman
            const ctxTrend = document.getElementById('chart-trend').getContext('2d');
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: data.trend.labels,
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: data.trend.data,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });

            // 2. Doughnut Chart: Distribusi Status
            const ctxStatus = document.getElementById('chart-status').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: data.status.labels,
                    datasets: [{
                        data: data.status.data,
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 2,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

        } catch (e) {
            console.error('Gagal mengambil data analitik:', e);
        }
    });
</script>
@endpush
@endif
