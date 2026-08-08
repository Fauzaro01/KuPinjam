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
                <table class="table-base datatable w-full" data-server-paginated="true">
                    <thead>
                        <tr>
                            <th style="width:13%">Karyawan</th>
                            <th style="width:14%">Kendaraan</th>
                            <th style="width:9%">Tgl Pinjam</th>
                            <th style="width:13%">Tgl Diajukan</th>
                            <th>Catatan</th>
                            <th style="width:10%">Status</th>
                            <th style="width:8%">Rating</th>
                            <th style="width:18%">Aksi</th>
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
                                {{-- Rating Column --}}
                                <td>
                                    @if($r->kondisi_rating)
                                        <div class="flex items-center gap-0.5" title="{{ $r->kondisi_feedback }}">
                                            @for($s=1;$s<=5;$s++)
                                                <svg class="w-3.5 h-3.5 {{ $s <= $r->kondisi_rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}"
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">&mdash;</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap">
                                    @if($r->status === 'pending')
                                        {{-- Rating Modal per baris --}}
                                        <div x-data="{ open: false, rating: 0, hover: 0, feedback: '' }" class="flex items-center gap-2">
                                            {{-- Trigger konfirmasi --}}
                                            <button @click="open = true"
                                                    class="btn-success text-xs py-1 px-3">
                                                Konfirmasi
                                            </button>

                                            {{-- Modal Overlay --}}
                                            <div x-show="open"
                                                 x-transition.opacity
                                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4"
                                                 style="display:none"
                                                 @keydown.escape.window="open=false">
                                                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-5"
                                                     @click.stop>
                                                    <div class="flex items-center justify-between">
                                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Konfirmasi Pengembalian</h3>
                                                        <button @click="open=false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        Kendaraan <strong class="text-gray-700 dark:text-gray-200">{{ $r->peminjaman?->kendaraan?->plat_nomor }}</strong>
                                                        oleh <strong class="text-gray-700 dark:text-gray-200">{{ $r->peminjaman?->user?->username }}</strong>.
                                                        Nilai kondisi kendaraan setelah dikembalikan.
                                                    </p>

                                                    {{-- Bintang --}}
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kondisi Kendaraan</p>
                                                        <div class="flex items-center gap-1">
                                                            @for($s=1;$s<=5;$s++)
                                                            <button type="button"
                                                                    @click="rating = {{ $s }}"
                                                                    @mouseenter="hover = {{ $s }}"
                                                                    @mouseleave="hover = 0"
                                                                    class="focus:outline-none">
                                                                <svg class="w-8 h-8 transition-colors"
                                                                     :class="(hover || rating) >= {{ $s }} ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600'"
                                                                     fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            </button>
                                                            @endfor
                                                            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400"
                                                                  x-text="['','Sangat Buruk','Buruk','Cukup','Baik','Sangat Baik'][rating] || 'Pilih rating'">
                                                            </span>
                                                        </div>
                                                    </div>

                                                    {{-- Feedback --}}
                                                    <div>
                                                        <label class="label-base">Catatan Kondisi <span class="text-gray-400 font-normal">(opsional)</span></label>
                                                        <textarea x-model="feedback" rows="3"
                                                                  placeholder="Contoh: Bumper sedikit lecet, perlu perawatan ban..."
                                                                  class="input-base mt-1"></textarea>
                                                    </div>

                                                    {{-- Submit --}}
                                                    <form method="POST" action="{{ route('pengembalian.konfirmasi', $r) }}">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="kondisi_rating" :value="rating">
                                                        <input type="hidden" name="kondisi_feedback" :value="feedback">
                                                        <div class="flex justify-end gap-3">
                                                            <button type="button" @click="open=false" class="btn-secondary text-sm">Batal</button>
                                                            <button type="submit"
                                                                    :disabled="rating === 0"
                                                                    class="btn-success text-sm"
                                                                    :class="rating === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                                                                Konfirmasi & Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- Tombol Tolak --}}
                                            <form method="POST"
                                                  action="{{ route('pengembalian.tolak', $r) }}"
                                                  onsubmit="return confirm('Tolak pengajuan pengembalian ini?')">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn-danger text-xs py-1 px-3">Tolak</button>
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
            @if($riwayats->hasPages())
                <div class="mt-4 px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                    {{ $riwayats->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
