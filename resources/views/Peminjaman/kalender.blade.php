@extends('layouts.default-dashboard')

@section('title', 'Kalender Jadwal Peminjaman')

@section('content')
<div class="space-y-6" x-data="calendarApp()">
    {{-- Header & Filter --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jadwal Armada</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Gunakan kalender interaktif untuk merencanakan & memantau peminjaman kendaraan.</p>
        </div>
        
        {{-- Filter Kendaraan --}}
        <div class="flex items-center gap-2">
            <label for="filter-kendaraan" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Filter Unit:</label>
            <select id="filter-kendaraan" x-model="selectedKendaraan" @change="refetchEvents()"
                    class="input-base py-1.5 px-3 text-sm min-w-[200px]">
                <option value="">-- Semua Kendaraan --</option>
                @foreach($kendaraans as $k)
                    <option value="{{ $k->id }}">{{ $k->plat_nomor }} - {{ $k->merk }} {{ $k->model }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Kalender Card --}}
    <div class="card shadow-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6">
        <div id="calendar" class="min-h-[550px]" x-ref="calendarEl"></div>
    </div>

    {{-- Detail Modal --}}
    <div x-show="detailOpen"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4"
         style="display:none"
         @keydown.escape.window="detailOpen = false">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4"
             @click.stop>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Detail Jadwal Peminjaman</h3>
                <button @click="detailOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-slate-700/80 text-sm space-y-3">
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500 dark:text-gray-400">Peminjam</span>
                    <strong class="text-gray-900 dark:text-white" x-text="selectedEvent.user"></strong>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500 dark:text-gray-400">Kendaraan</span>
                    <div>
                        <strong class="text-gray-900 dark:text-white block text-right" x-text="selectedEvent.kendaraan"></strong>
                        <span class="text-xs text-gray-400 dark:text-gray-500 block text-right" x-text="selectedEvent.plat_nomor"></span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500 dark:text-gray-400">Tanggal Pinjam</span>
                    <span class="text-gray-900 dark:text-white" x-text="formatDateTime(selectedEvent.start)"></span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500 dark:text-gray-400">Batas Pengembalian</span>
                    <span class="text-gray-900 dark:text-white" x-text="formatDateTime(selectedEvent.end)"></span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500 dark:text-gray-400">Tujuan</span>
                    <span class="text-gray-900 dark:text-white text-right max-w-[200px] truncate" x-text="selectedEvent.tujuan"></span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-gray-500 dark:text-gray-400">Status</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                          :class="selectedEvent.status === 'dipinjam' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800'"
                          x-text="selectedEvent.status === 'dipinjam' ? 'Dipinjam' : 'Selesai'"></span>
                </div>
            </div>

            <div class="flex justify-end pt-3">
                <button @click="detailOpen = false" class="btn-primary w-full text-center">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Load FullCalendar v6 via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
    /* FullCalendar Styling Tweaks for Elegant Theme */
    .fc {
        font-family: inherit;
    }
    .fc .fc-toolbar-title {
        font-size: 1.125rem !important;
        font-weight: 700;
        color: #1f2937;
    }
    .dark .fc .fc-toolbar-title {
        color: #f3f4f6;
    }
    .fc .fc-button-primary {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
        font-size: 0.8125rem;
        font-weight: 500;
        text-transform: capitalize;
    }
    .fc .fc-button-primary:hover {
        background-color: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
    }
    .fc .fc-button-active {
        background-color: #1e40af !important;
        border-color: #1e40af !important;
    }
    /* Event styles */
    .fc-event {
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 6px !important;
        border: none !important;
        font-size: 0.75rem !important;
        font-weight: 600;
    }
    /* Table borders color */
    .fc td, .fc th {
        border-color: #e5e7eb !important;
    }
    .dark .fc td, .dark .fc th {
        border-color: #334155 !important;
    }
    .dark .fc-theme-standard td, .dark .fc-theme-standard th {
        border-color: #334155 !important;
    }
    .dark .fc-day-today {
        background-color: rgba(30, 41, 59, 0.5) !important;
    }
</style>

<script>
    function calendarApp() {
        return {
            selectedKendaraan: '',
            detailOpen: false,
            selectedEvent: {},
            calendar: null,

            init() {
                // Tunggu DOM dan FullCalendar terload sepenuhnya
                this.$nextTick(() => {
                    const calendarEl = this.$refs.calendarEl;
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'id',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek'
                        },
                        buttonText: {
                            today: 'Hari Ini',
                            month: 'Bulan',
                            week: 'Minggu'
                        },
                        events: async (info, successCallback, failureCallback) => {
                            try {
                                let url = `{{ route('peminjaman.api-kalender') }}`;
                                if (this.selectedKendaraan) {
                                    url += `?kendaraan_id=${this.selectedKendaraan}`;
                                }
                                const res = await fetch(url);
                                const rawEvents = await res.json();
                                
                                // Map events ke struktur FullCalendar
                                const events = rawEvents.map(e => ({
                                    id: e.id,
                                    title: `${e.plat_nomor} (${e.user})`,
                                    start: e.start,
                                    end: e.end,
                                    backgroundColor: e.status === 'dipinjam' ? '#3B82F6' : '#10B981', // Blue vs Emerald
                                    extendedProps: e
                                }));
                                
                                successCallback(events);
                            } catch(err) {
                                console.error("Gagal mengambil event kalender:", err);
                                failureCallback(err);
                            }
                        },
                        eventClick: (info) => {
                            this.selectedEvent = info.event.extendedProps;
                            this.detailOpen = true;
                        }
                    });
                    
                    calendar.render();
                    this.calendar = calendar;
                });
            },

            refetchEvents() {
                if (this.calendar) {
                    this.calendar.refetchEvents();
                }
            },

            formatDateTime(isoString) {
                if (!isoString) return '';
                const date = new Date(isoString);
                return date.toLocaleString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        };
    }
</script>
@endsection
