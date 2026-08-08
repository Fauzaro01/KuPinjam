<!DOCTYPE html>
<html lang="id" x-data>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KuPinjam')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    {{-- Anti-FOUC: apply dark mode sebelum paint (harus di <head>) --}}
    <script>
        (function () {
            if (localStorage.getItem('darkmode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-100 dark:bg-slate-900 text-gray-900 dark:text-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('layouts.sidebar-dashboard')

        {{-- Main content area --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-auto">

            {{-- Global header (desktop & mobile) --}}
            <header class="sticky top-0 z-20 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700
                           px-6 py-3 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    {{-- Hamburger (mobile only) --}}
                    <button id="sidebar-toggle" type="button"
                            class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    {{-- Judul Halaman / Breadcrumb --}}
                    <span class="text-lg font-bold hidden sm:inline-block tracking-tight text-gray-800 dark:text-white">
                        @yield('title', 'KuPinjam')
                    </span>

                    {{-- Tombol Spotlight Search --}}
                    <button id="spotlight-trigger" type="button"
                            class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-slate-700/60 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors text-sm"
                            aria-label="Pencarian Global (Ctrl+K)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                        <span class="hidden md:inline">Cari sesuatu...</span>
                        <kbd class="hidden md:inline-flex items-center px-1.5 py-0.5 text-[10px] font-medium bg-gray-200 dark:bg-slate-600 rounded border border-gray-300 dark:border-slate-500">Ctrl K</kbd>
                    </button>
                </div>

                {{-- Sisi kanan: Notifikasi Lonceng --}}
                <div class="flex items-center gap-4" x-data="{ notifOpen: false }">
                    {{-- Notifikasi Dropdown --}}
                    <div class="relative">
                        <button
                            @click="notifOpen = !notifOpen"
                            type="button"
                            class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full relative transition-colors focus:outline-none"
                            aria-label="Notifikasi"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center min-w-[1.1rem] h-[1.1rem] px-0.5 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none">
                                    {{ $unreadNotifications->count() > 9 ? '9+' : $unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Panel --}}
                        <div
                            x-show="notifOpen"
                            @click.away="notifOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-200 dark:border-slate-700 overflow-hidden z-30"
                            style="display: none;"
                        >
                            {{-- Header dropdown --}}
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50 dark:bg-slate-700/50">
                                <span class="font-semibold text-sm">Notifikasi</span>
                                @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notifications.read-all') }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs text-primary hover:underline font-medium">Tandai semua dibaca</button>
                                    </form>
                                @endif
                            </div>

                            {{-- List notifikasi --}}
                            <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-slate-700">
                                @forelse($unreadNotifications ?? [] as $n)
                                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-slate-700/30 flex items-start justify-between gap-2">
                                        <div class="space-y-1">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $n->title }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $n->message }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $n->created_at->diffForHumans() }}</p>
                                        </div>
                                        <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-gray-400 hover:text-primary p-1 rounded-lg" title="Tandai dibaca">
                                                ✓
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="p-6 text-center text-gray-400 dark:text-gray-500 text-sm">
                                        Tidak ada notifikasi baru.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Announcement Banners --}}
            @if(isset($activeAnnouncements) && $activeAnnouncements->isNotEmpty())
                <div x-data="{ dismissed: JSON.parse(localStorage.getItem('dismissed_announcements') || '[]') }">
                    @foreach($activeAnnouncements as $ann)
                    <div x-show="!dismissed.includes({{ $ann->id }})"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 max-h-20"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="flex items-start gap-3 px-5 py-3 bg-blue-50 dark:bg-blue-900/30 border-b border-blue-100 dark:border-blue-800/50 text-sm text-blue-800 dark:text-blue-200">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <span class="font-semibold">{{ $ann->title }}:</span>
                            <span class="ml-1 opacity-90">{{ $ann->content }}</span>
                        </div>
                        <button @click="dismissed.push({{ $ann->id }}); localStorage.setItem('dismissed_announcements', JSON.stringify(dismissed))"
                                class="flex-shrink-0 text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 transition-colors p-0.5 rounded" title="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
            @endif

            {{-- Page content --}}
            <main class="flex-1 p-6">
                <x-alert />
                @yield('content')
            </main>

            {{-- Footer --}}
            @include('layouts.footer-dashboard')
        </div>
    </div>

    {{-- ============ SPOTLIGHT SEARCH MODAL ============ --}}
    <div id="spotlight-modal"
         class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4 bg-black/50 backdrop-blur-sm"
         style="display:none!important"
         x-data="spotlightSearch()"
         x-init="init()"
         x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="close()"
    >
        {{-- Inner box --}}
        <div class="w-full max-w-xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-slate-700 overflow-hidden"
             x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.stop>

            {{-- Input pencarian --}}
            <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100 dark:border-slate-700">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input
                    id="spotlight-input"
                    type="text"
                    x-model="query"
                    @input.debounce.300ms="doSearch()"
                    placeholder="Cari kendaraan, pengguna, atau peminjaman..."
                    class="flex-1 bg-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm outline-none"
                    autocomplete="off"
                />
                <kbd @click="close()" class="cursor-pointer text-xs text-gray-400 bg-gray-100 dark:bg-slate-700 px-1.5 py-0.5 rounded border border-gray-200 dark:border-slate-600">ESC</kbd>
            </div>

            {{-- Hasil pencarian --}}
            <div class="max-h-80 overflow-y-auto">

                {{-- Loading state --}}
                <div x-show="loading" class="p-6 text-center text-sm text-gray-400">
                    <svg class="w-5 h-5 mx-auto animate-spin text-primary mb-1" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Mencari...
                </div>

                {{-- No results --}}
                <div x-show="!loading && query.length >= 2 && results.length === 0"
                     class="p-6 text-center text-sm text-gray-400 dark:text-gray-500">
                    Tidak ada hasil untuk "<span x-text="query" class="font-medium"></span>".
                </div>

                {{-- Hint state --}}
                <div x-show="!loading && query.length < 2"
                     class="px-4 py-5 text-center text-xs text-gray-400 dark:text-gray-500 space-y-1">
                    <p>Ketik minimal 2 karakter untuk mencari.</p>
                    <p class="text-[11px]">Cari kendaraan (plat/merk), pengguna, atau peminjaman.</p>
                </div>

                {{-- Result list --}}
                <template x-if="!loading && results.length > 0">
                    <div class="divide-y divide-gray-100 dark:divide-slate-700">
                        <template x-for="(item, i) in results" :key="i">
                            <a :href="item.url"
                               @click="close()"
                               class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-700/40 transition-colors group">

                                {{-- Type Icon --}}
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                     :class="{
                                         'bg-blue-100 dark:bg-blue-900/40'  : item.icon === 'car',
                                         'bg-violet-100 dark:bg-violet-900/40': item.icon === 'user',
                                         'bg-amber-100 dark:bg-amber-900/40' : item.icon === 'document'
                                     }">
                                    {{-- car --}}
                                    <svg x-show="item.icon === 'car'" class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h12l2-2V9l-3-5H9"/>
                                    </svg>
                                    {{-- user --}}
                                    <svg x-show="item.icon === 'user'" class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{-- document --}}
                                    <svg x-show="item.icon === 'document'" class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="item.label"></p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 truncate" x-text="item.sublabel"></p>
                                </div>
                                <span class="text-[10px] text-gray-400 bg-gray-100 dark:bg-slate-700 px-1.5 py-0.5 rounded font-medium" x-text="item.type"></span>
                            </a>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Footer hint --}}
            <div class="px-4 py-2 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/30 flex items-center gap-4 text-[10px] text-gray-400">
                <span><kbd class="bg-gray-200 dark:bg-slate-600 px-1 rounded">↵</kbd> Buka</span>
                <span><kbd class="bg-gray-200 dark:bg-slate-600 px-1 rounded">ESC</kbd> Tutup</span>
            </div>
        </div>

        {{-- Click outside to close --}}
        <div class="absolute inset-0 -z-10" @click="close()"></div>
    </div>

    {{-- Alpine.js Spotlight Script --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('spotlightSearch', () => ({
                open: false,
                query: '',
                results: [],
                loading: false,
                _timer: null,

                init() {
                    // Tombol di header
                    const trigger = document.getElementById('spotlight-trigger');
                    if (trigger) trigger.addEventListener('click', () => this.openModal());

                    // Keyboard shortcut Ctrl+K
                    document.addEventListener('keydown', (e) => {
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            this.openModal();
                        }
                    });
                },

                openModal() {
                    this.open = true;
                    this.query = '';
                    this.results = [];
                    this.$nextTick(() => {
                        document.getElementById('spotlight-input')?.focus();
                    });
                },

                close() {
                    this.open = false;
                    this.query = '';
                    this.results = [];
                },

                async doSearch() {
                    if (this.query.length < 2) { this.results = []; return; }
                    this.loading = true;
                    try {
                        const res = await fetch(`{{ route('search') }}?q=${encodeURIComponent(this.query)}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        this.results = data.results || [];
                    } catch(e) {
                        this.results = [];
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>

    {{-- DataTables JS via CDN (after jQuery) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    @stack('scripts')
</body>
</html>
