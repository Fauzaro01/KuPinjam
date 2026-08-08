@extends('layouts.default-dashboard')

@section('title', 'Kendaraan')

@section('content')
{{-- Modal state di-scope ke seluruh halaman --}}
<div
    class="space-y-6"
    x-data="{
        modalOpen: false,
        kendaraanId: '',
        kendaraanLabel: '',
        tanggalPinjam: '',
        tanggalKembali: '',
        tujuan: '',
        keterangan: ''
    }"
    @open-modal-pinjam.window="
        kendaraanId    = $event.detail.id;
        kendaraanLabel = $event.detail.plat + ' — ' + $event.detail.label;
        tanggalPinjam  = '';
        tanggalKembali = '';
        tujuan         = '';
        keterangan     = '';
        modalOpen      = true;
    "
>
    {{-- Page heading --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kendaraan</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Daftar kendaraan perusahaan</p>
        </div>
        @if(!$showTrashed)
            @can('create', \App\Models\Kendaraan::class)
                <a href="{{ route('kendaraan.create') }}" class="btn-primary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Kendaraan
                </a>
            @endcan
        @endif
    </div>

    {{-- Tab Filter Aktif / Dihapus untuk Admin --}}
    @if(Auth::user()->hasRole('administrator'))
        <div class="flex items-center gap-2 border-b border-gray-200 dark:border-slate-700">
            <a href="{{ route('kendaraan.index') }}"
               class="pb-2 px-1 text-sm font-medium border-b-2 transition-colors
                      {{ !$showTrashed ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                Kendaraan Aktif
            </a>
            <a href="{{ route('kendaraan.index', ['trashed' => 1]) }}"
               class="pb-2 px-1 text-sm font-medium border-b-2 transition-colors
                      {{ $showTrashed ? 'border-red-500 text-red-500' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                Dihapus
            </a>
        </div>
    @endif

    {{-- Tabel --}}
    <div class="card">
        <div class="table-container">
            <table class="table-base datatable w-full" data-server-paginated="true">
                <thead>
                    <tr>
                        <th style="width:14%">Plat Nomor</th>
                        <th style="width:14%">Merk</th>
                        <th style="width:14%">Model</th>
                        <th style="width:8%">Tahun</th>
                        <th style="width:10%">Jenis</th>
                        <th style="width:10%">Status</th>
                        <th style="width:30%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraans as $kendaraan)
                        <tr class="{{ $showTrashed ? 'opacity-70' : '' }}">
                            <td class="font-semibold">
                                {{ $kendaraan->plat_nomor }}
                                @if($showTrashed)
                                    <span class="badge-red text-xs ml-1">Dihapus</span>
                                @endif
                            </td>
                            <td>{{ $kendaraan->merk }}</td>
                            <td>{{ $kendaraan->model }}</td>
                            <td>{{ $kendaraan->tahun }}</td>
                            <td class="capitalize">{{ $kendaraan->jenis_kendaraan }}</td>
                            <td>
                                @if($kendaraan->status === 'tersedia')
                                    <span class="badge-green">Tersedia</span>
                                @elseif($kendaraan->status === 'dipinjam')
                                    <span class="badge-yellow">Dipinjam</span>
                                @else
                                    <span class="badge-red">Perawatan</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap">
                                @if($showTrashed)
                                    {{-- Restore kendaraan --}}
                                    <form method="POST"
                                          action="{{ route('kendaraan.restore', $kendaraan->id) }}"
                                          onsubmit="return confirm('Pulihkan kendaraan ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-secondary text-xs py-1 px-3 text-green-700 border-green-400 hover:bg-green-50 dark:text-green-400 dark:border-green-600 dark:hover:bg-green-900/30">
                                            Pulihkan
                                        </button>
                                    </form>
                                @elseif(Auth::user()->hasRole('karyawan') && $kendaraan->status === 'tersedia')
                                    {{-- Karyawan: tombol pinjam membuka modal --}}
                                    <button
                                        type="button"
                                        @click="$dispatch('open-modal-pinjam', {
                                            id:    '{{ $kendaraan->id }}',
                                            plat:  '{{ $kendaraan->plat_nomor }}',
                                            label: '{{ addslashes($kendaraan->merk . ' ' . $kendaraan->model) }}'
                                        })"
                                        class="btn-primary text-xs py-1 px-3">
                                        Pinjam
                                    </button>
                                @elseif(Auth::user()->hasRole('karyawan'))
                                    <span class="text-xs text-gray-400 dark:text-gray-500">Tidak tersedia</span>
                                @else
                                    {{-- Administrator: tombol edit & hapus --}}
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('kendaraan.edit', $kendaraan) }}"
                                           class="btn-secondary text-xs py-1 px-3">Edit</a>
                                        <form method="POST"
                                              action="{{ route('kendaraan.destroy', $kendaraan) }}"
                                              onsubmit="return confirm('Hapus kendaraan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger text-xs py-1 px-3">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-400">
                                {{ $showTrashed ? 'Tidak ada kendaraan yang dihapus.' : 'Belum ada data kendaraan.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kendaraans->hasPages())
            <div class="mt-4">
                {{ $kendaraans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- ====================================================
         Modal Pinjam Kendaraan (karyawan only, Alpine.js)
         ==================================================== --}}
    <div
        x-show="modalOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
        @keydown.escape.window="modalOpen = false"
        style="display: none;"
    >
        {{-- Panel modal --}}
        <div
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full max-w-lg bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden"
            @click.outside="modalOpen = false"
        >
            {{-- Header modal --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ajukan Peminjaman</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" x-text="kendaraanLabel"></p>
                </div>
                <button
                    type="button"
                    @click="modalOpen = false"
                    class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100
                           dark:hover:text-gray-300 dark:hover:bg-slate-700 transition-colors"
                    aria-label="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form
                method="POST"
                action="{{ route('peminjaman.pinjam') }}"
                enctype="multipart/form-data"
                x-data="{ loading: false }"
                @submit="loading = true"
            >
                @csrf
                <input type="hidden" name="kendaraan_id" :value="kendaraanId">

                <div class="px-6 py-5 space-y-4">
                    {{-- Tanggal pinjam & kembali --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Tanggal Pinjam
                            </label>
                            <input
                                type="date"
                                name="tanggal_pinjam"
                                x-model="tanggalPinjam"
                                :min="new Date().toISOString().split('T')[0]"
                                class="input-field"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Tanggal Kembali
                            </label>
                            <input
                                type="date"
                                name="tanggal_kembali"
                                x-model="tanggalKembali"
                                :min="tanggalPinjam || new Date().toISOString().split('T')[0]"
                                class="input-field"
                                required>
                        </div>
                    </div>

                    {{-- Tujuan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Tujuan Peminjaman
                        </label>
                        <input
                            type="text"
                            name="tujuan"
                            x-model="tujuan"
                            class="input-field"
                            placeholder="Perjalanan dinas ke..."
                            maxlength="255"
                            required>
                    </div>

                    {{-- Keterangan (opsional) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Keterangan
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea
                            name="keterangan"
                            x-model="keterangan"
                            rows="3"
                            class="input-field resize-none"
                            placeholder="Catatan tambahan..."></textarea>
                    </div>

                    {{-- Upload Dokumen Lampiran (opsional) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Upload Dokumen / Surat Tugas / Bukti Foto
                            <span class="text-gray-400 font-normal">(opsional, maks 3 file, maks 5MB/file)</span>
                        </label>
                        <input
                            type="file"
                            name="dokumens[]"
                            multiple
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="input-field py-1 text-sm bg-white dark:bg-slate-700"
                        />
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Format: PDF, JPG, PNG, JPEG</p>
                    </div>
                </div>

                {{-- Footer tombol --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4
                            border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50">
                    <button
                        type="button"
                        @click="modalOpen = false"
                        class="btn-secondary"
                        :disabled="loading">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="btn-primary flex items-center gap-2"
                        :disabled="loading">
                        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span x-text="loading ? 'Memproses...' : 'Ajukan Peminjaman'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
