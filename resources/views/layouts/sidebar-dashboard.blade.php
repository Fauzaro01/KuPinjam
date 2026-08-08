{{-- Overlay untuk mobile --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"></div>

{{-- Sidebar --}}
<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-800 dark:bg-slate-900 text-white
           flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out
           lg:relative lg:translate-x-0 lg:flex"
>
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-700">
        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h12l2-2V9l-3-5H9"/>
            </svg>
        </div>
        <span class="text-lg font-bold tracking-tight">KuPinjam</span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Kendaraan --}}
        <a href="{{ route('kendaraan.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('kendaraan.*') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h12l2-2V9l-3-5H9"/>
            </svg>
            Kendaraan
        </a>

        {{-- Peminjaman --}}
        <a href="{{ route('peminjaman.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('peminjaman.index') || request()->routeIs('peminjaman.create') || request()->routeIs('peminjaman.edit') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Peminjaman
        </a>

        {{-- Kalender Jadwal --}}
        <a href="{{ route('peminjaman.kalender') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('peminjaman.kalender') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Kalender Jadwal
        </a>

        {{-- Menu khusus Administrator --}}
        @if(Auth::user()->hasRole('administrator'))
            {{-- Pengembalian --}}
            <a href="{{ route('pengembalian.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('pengembalian.*') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                <span class="flex-1">Pengembalian</span>
                @if($pendingSidebar > 0)
                    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1
                                 rounded-full bg-red-500 text-white text-xs font-bold leading-none">
                        {{ $pendingSidebar }}
                    </span>
                @endif
            </a>

            {{-- User Management --}}
            <a href="{{ route('usermanagement.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('usermanagement.*') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Manajemen User
            </a>

            {{-- Log Aktivitas --}}
            <a href="{{ route('admin.activity-log') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.activity-log') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Log Aktivitas
            </a>

            {{-- Laporan --}}
            <a href="{{ route('admin.laporan') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('admin.laporan') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h12l2-2V9l-3-5H9"/>
                </svg>
                Laporan Peminjaman
            </a>

            {{-- Perawatan --}}
            <a href="{{ route('perawatan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('perawatan.*') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Perawatan Kendaraan
            </a>

            {{-- Pengumuman --}}
            <a href="{{ route('announcement.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('announcement.*') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Pengumuman
            </a>
        @endif

        {{-- Divider --}}
        <div class="pt-2 pb-1">
            <div class="border-t border-slate-700"></div>
        </div>

        {{-- Profil --}}
        <a href="{{ route('profile.show') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('profile.*') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil Saya
        </a>

        {{-- Keamanan Akun --}}
        <a href="{{ route('accountSecurity') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('accountSecurity') ? 'bg-primary text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Keamanan Akun
        </a>
    </nav>

    {{-- Footer sidebar: user info + dark mode + logout --}}
    <div class="px-4 py-4 border-t border-slate-700 space-y-2">
        {{-- User info --}}
        <div class="flex items-center gap-3 px-3 py-2">
            @if(Auth::user()->avatar)
                <img src="{{ Storage::url(Auth::user()->avatar) }}"
                     alt="Avatar"
                     class="w-8 h-8 rounded-full object-cover flex-shrink-0">
            @else
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-xs font-semibold">
                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                    </span>
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->username }}</p>
                <p class="text-xs text-slate-400 capitalize">{{ Auth::user()->role }}</p>
            </div>
        </div>

        {{-- Dark Mode Toggle --}}
        <button
            onclick="toggleDarkMode()"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300
                   hover:bg-slate-700 hover:text-white transition-colors duration-150"
        >
            <svg class="w-5 h-5 flex-shrink-0 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg class="w-5 h-5 flex-shrink-0 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="dark:hidden">Mode Gelap</span>
            <span class="hidden dark:block">Mode Terang</span>
        </button>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300
                           hover:bg-red-600 hover:text-white transition-colors duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
