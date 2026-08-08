<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama | KuPinjam</title>
    {{-- Task 39.1: favicon lokal, hapus bintangmas-engineering.com --}}
    <link rel="icon" href="/images/logo/kupinjam.webp" type="image/webp">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Task 39.3: hapus heroicons CDN yang tidak terpakai --}}
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }
        .animate-float         { animation: float 3s ease-in-out infinite; }
        .animate-float-delayed { animation: float 3s ease-in-out infinite 1.5s; }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">

{{-- ═══════════════════════════════════════════════════════ NAVBAR ═══ --}}
{{-- Task 40: Intersection Observer active state, icon hamburger/X toggle --}}
<div x-data="{ showGuide: false }">

<nav class="bg-white/90 backdrop-blur-md shadow-lg sticky top-0 z-50 transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-lg shadow-md overflow-hidden bg-white flex items-center justify-center">
                    <img src="/images/logo/kupinjam.webp" alt="KuPinjam Logo" class="h-full w-full object-cover">
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold text-gray-900">KuPinjam</span>
                    <span class="text-xs text-gray-500">Vehicle Rental System</span>
                </div>
            </div>

            <!-- Desktop Nav (Task 40.1: active state via JS Intersection Observer) -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8" id="desktop-nav">
                    <a href="#home"         data-section="home"         class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Home</a>
                    <a href="#features"     data-section="features"     class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Fitur</a>
                    <a href="#stats"        data-section="stats"        class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Statistik</a>
                    <a href="#how-it-works" data-section="how-it-works" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Cara Kerja</a>
                    <a href="#about"        data-section="about"        class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Tentang</a>
                </div>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-4 py-2 rounded-lg text-sm font-medium transition-all hover:bg-blue-50">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">Daftar</a>
                @else
                    <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-2 rounded-lg text-sm font-medium shadow-lg transition-all transform hover:-translate-y-0.5">Dashboard</a>
                @endguest
            </div>

            <!-- Task 40.2: Mobile hamburger/X icon toggle -->
            <div class="md:hidden">
                <button id="mobile-menu-button" type="button" class="text-gray-700 hover:text-blue-600 p-2 rounded-md transition-colors duration-200" aria-label="Toggle menu">
                    <svg id="icon-hamburger" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="icon-close" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
        <div class="px-2 pt-2 pb-3 space-y-1" id="mobile-nav">
            <a href="#home"         class="mobile-nav-link text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Home</a>
            <a href="#features"     class="mobile-nav-link text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Fitur</a>
            <a href="#stats"        class="mobile-nav-link text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Statistik</a>
            <a href="#how-it-works" class="mobile-nav-link text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Cara Kerja</a>
            <a href="#about"        class="mobile-nav-link text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Tentang</a>
            <div class="pt-4 border-t border-gray-200">
                @guest
                    <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg font-medium mb-2">Masuk</a>
                    <a href="{{ route('register') }}" class="block w-full text-center border-2 border-blue-600 text-blue-600 px-4 py-2 rounded-lg font-medium">Daftar</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block w-full text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium">Dashboard</a>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- ════════════════════════════════════════════════════════ HERO ═══ --}}
{{-- Task 39.2: Alpine modal panduan; Task 41: SVG illustration, floating cards --}}
<section id="home" class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-purple-50"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32">
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
            <!-- Content -->
            <div class="mb-12 lg:mb-0">
                <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium mb-8 animate-pulse">
                    <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                    Sistem Peminjaman Kendaraan Terpercaya
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Pinjam Kendaraan
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Dengan Mudah</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Solusi terdepan untuk peminjaman kendaraan perusahaan. Kelola, pantau, dan gunakan kendaraan dengan sistem yang aman, efisien, dan mudah digunakan.
                </p>

                <!-- CTA Buttons — Task 39.2: tombol panduan pakai Alpine modal -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button @click="showGuide = true"
                            class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 group">
                        <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Panduan Penggunaan
                    </button>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold rounded-lg transition-all group">
                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        Mulai Meminjam
                    </a>
                </div>

                <!-- Hero mini-stats -->
                <div class="mt-12 grid grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">24/7</div>
                        <div class="text-gray-600 text-sm">Akses Sistem</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">100%</div>
                        <div class="text-gray-600 text-sm">Aman & Terpercaya</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">Fast</div>
                        <div class="text-gray-600 text-sm">Proses Cepat</div>
                    </div>
                </div>
            </div>

            <!-- Task 41.1: SVG vehicle illustration (ganti logo-min-1.png) -->
            <!-- Task 41.2: floating cards offset diperbaiki agar tidak clip di mobile -->
            <div class="relative flex items-center justify-center mt-8 lg:mt-0">
                <div class="relative w-full max-w-sm mx-auto">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-500 rounded-3xl transform rotate-3 shadow-2xl"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl p-10 transform -rotate-1">
                        <!-- SVG Vehicle Illustration inline -->
                        <svg viewBox="0 0 400 260" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Ilustrasi Kendaraan" class="w-full h-auto">
                            <!-- Road -->
                            <rect x="0" y="210" width="400" height="50" rx="4" fill="#E2E8F0"/>
                            <rect x="30" y="232" width="60" height="6" rx="3" fill="#CBD5E1"/>
                            <rect x="120" y="232" width="60" height="6" rx="3" fill="#CBD5E1"/>
                            <rect x="210" y="232" width="60" height="6" rx="3" fill="#CBD5E1"/>
                            <rect x="300" y="232" width="60" height="6" rx="3" fill="#CBD5E1"/>
                            <!-- Car body -->
                            <rect x="60" y="145" width="280" height="70" rx="12" fill="#3B82F6"/>
                            <!-- Cabin -->
                            <path d="M120 145 L150 95 L260 95 L290 145 Z" fill="#2563EB"/>
                            <!-- Windows -->
                            <rect x="158" y="100" width="38" height="36" rx="4" fill="#BAE6FD" opacity="0.9"/>
                            <rect x="206" y="100" width="38" height="36" rx="4" fill="#BAE6FD" opacity="0.9"/>
                            <!-- Wheels -->
                            <circle cx="130" cy="215" r="28" fill="#1E293B"/>
                            <circle cx="130" cy="215" r="16" fill="#475569"/>
                            <circle cx="130" cy="215" r="6"  fill="#94A3B8"/>
                            <circle cx="270" cy="215" r="28" fill="#1E293B"/>
                            <circle cx="270" cy="215" r="16" fill="#475569"/>
                            <circle cx="270" cy="215" r="6"  fill="#94A3B8"/>
                            <!-- Headlights -->
                            <rect x="62" y="160" width="22" height="14" rx="4" fill="#FDE68A"/>
                            <rect x="316" y="160" width="22" height="14" rx="4" fill="#FCA5A5"/>
                            <!-- Door lines -->
                            <line x1="200" y1="148" x2="200" y2="212" stroke="#2563EB" stroke-width="2"/>
                        </svg>
                    </div>

                    <!-- Floating card kiri atas — offset diperbaiki Task 41.2 -->
                    <div class="absolute top-3 -left-4 sm:-left-8 bg-white rounded-xl shadow-lg p-3 animate-float z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-900">Verified System</div>
                                <div class="text-xs text-gray-500">100% Secure</div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating card kanan bawah -->
                    <div class="absolute bottom-3 -right-4 sm:-right-8 bg-white rounded-xl shadow-lg p-3 animate-float-delayed z-10">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-900">Fast Process</div>
                                <div class="text-xs text-gray-500">Quick & Easy</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════ FEATURES SECTION ═══ --}}
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Mengapa Memilih <span class="text-blue-600">KuPinjam?</span></h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Sistem peminjaman kendaraan modern dengan fitur-fitur canggih untuk memudahkan aktivitas Anda</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $features = [
                ['color'=>'blue',  'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title'=>'Peminjaman Real-time', 'desc'=>'Proses peminjaman yang cepat dan mudah dengan update status secara real-time untuk transparansi penuh.'],
                ['color'=>'green', 'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title'=>'Keamanan Terjamin', 'desc'=>'Sistem keamanan berlapis untuk melindungi data pribadi dan memastikan kendaraan dalam kondisi aman.'],
                ['color'=>'purple','icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title'=>'Laporan & Analitik', 'desc'=>'Dashboard komprehensif dengan laporan detail dan analitik penggunaan kendaraan untuk manajemen optimal.'],
                ['color'=>'orange','icon'=>'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'title'=>'Mobile Friendly', 'desc'=>'Akses sistem dari mana saja dengan tampilan responsif yang optimal di semua perangkat.'],
                ['color'=>'red',   'icon'=>'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'title'=>'Support 24/7', 'desc'=>'Dukungan teknis tersedia 24 jam untuk memastikan kelancaran operasional sistem Anda.'],
                ['color'=>'indigo','icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title'=>'User Experience', 'desc'=>'Interface yang intuitif dan user-friendly dirancang khusus untuk memberikan pengalaman terbaik.'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="group bg-gradient-to-br from-{{ $f['color'] }}-50 to-{{ $f['color'] }}-100 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-12 h-12 bg-{{ $f['color'] }}-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $f['title'] }}</h3>
                <p class="text-gray-600">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════ STATS SECTION (Task 42) ═══ --}}
<section id="stats" class="py-20 bg-gradient-to-r from-blue-600 to-purple-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">KuPinjam dalam Angka</h2>
            <p class="text-blue-100 text-lg">Kepercayaan yang terus bertumbuh bersama pengguna kami</p>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center stat-item">
                <div class="text-5xl font-extrabold text-white mb-2" data-target="{{ $totalKendaraan }}">0</div>
                <div class="text-blue-200 font-medium">Kendaraan Tersedia</div>
            </div>
            <div class="text-center stat-item">
                <div class="text-5xl font-extrabold text-white mb-2" data-target="{{ $totalAktif }}">0</div>
                <div class="text-blue-200 font-medium">Peminjaman Aktif</div>
            </div>
            <div class="text-center stat-item">
                <div class="text-5xl font-extrabold text-white mb-2" data-target="{{ $totalPengguna }}">0</div>
                <div class="text-blue-200 font-medium">Pengguna Terdaftar</div>
            </div>
            <div class="text-center stat-item">
                <div class="text-5xl font-extrabold text-white mb-2" data-target="{{ $kepuasan }}">0</div>
                <div class="text-blue-200 font-medium">Tingkat Kepuasan (%)</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════ HOW IT WORKS SECTION ═══ --}}
<section id="how-it-works" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Cara <span class="text-blue-600">Kerja</span> Sistem</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Proses peminjaman yang mudah dan efisien dalam 4 langkah sederhana</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
            $steps = [
                ['num'=>1,'color'=>'blue',  'title'=>'Registrasi Akun',    'desc'=>'Daftarkan akun Anda dengan informasi yang valid untuk mengakses sistem.'],
                ['num'=>2,'color'=>'green', 'title'=>'Pilih Kendaraan',    'desc'=>'Browse dan pilih kendaraan yang sesuai dari daftar yang tersedia.'],
                ['num'=>3,'color'=>'purple','title'=>'Submit Permintaan',   'desc'=>'Isi formulir peminjaman dengan detail keperluan dan waktu penggunaan.'],
                ['num'=>4,'color'=>'orange','title'=>'Gunakan Kendaraan',   'desc'=>'Setelah disetujui, ambil kendaraan dan gunakan sesuai ketentuan.'],
            ];
            @endphp
            @foreach($steps as $s)
            <div class="text-center group">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-{{ $s['color'] }}-600 rounded-full flex items-center justify-center mx-auto shadow-lg group-hover:shadow-xl transform group-hover:scale-110 transition-all duration-300">
                        <span class="text-2xl font-bold text-white">{{ $s['num'] }}</span>
                    </div>
                    @if($s['num'] < 4)
                    <div class="absolute top-10 left-1/2 w-full h-0.5 bg-gradient-to-r from-{{ $s['color'] }}-600 to-transparent transform translate-x-8 hidden lg:block"></div>
                    @endif
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $s['title'] }}</h3>
                <p class="text-gray-600">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════ ABOUT SECTION ═══ --}}
{{-- Task 39.1: hapus img bintangmas-engineering.com, ganti dengan SVG --}}
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
            <div class="mb-12 lg:mb-0">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Tentang <span class="text-blue-600">KuPinjam</span></h2>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    KuPinjam adalah solusi digital terdepan untuk manajemen peminjaman kendaraan perusahaan.
                    Kami menghadirkan sistem yang modern, aman, dan efisien untuk memudahkan proses peminjaman kendaraan dalam lingkungan kerja.
                </p>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Dengan teknologi terkini dan antarmuka yang user-friendly, KuPinjam membantu perusahaan mengoptimalkan
                    penggunaan armada kendaraan sambil memastikan transparansi dan akuntabilitas dalam setiap transaksi.
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-blue-50 rounded-xl p-6">
                        <div class="text-2xl font-bold text-blue-600 mb-2">2025</div>
                        <div class="text-gray-600">Tahun Berdiri</div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-6">
                        <div class="text-2xl font-bold text-green-600 mb-2">100%</div>
                        <div class="text-gray-600">Client Satisfaction</div>
                    </div>
                </div>
            </div>
            <!-- SVG illustration gantikan img eksternal -->
            <div class="relative flex items-center justify-center">
                <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-3xl p-10 w-full max-w-sm mx-auto">
                    <svg viewBox="0 0 300 240" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Ilustrasi Manajemen Kendaraan" class="w-full h-auto">
                        <!-- Building/Office -->
                        <rect x="60" y="40" width="180" height="160" rx="8" fill="#E0E7FF"/>
                        <rect x="80" y="60" width="140" height="120" rx="4" fill="#C7D2FE"/>
                        <!-- Windows -->
                        <rect x="90" y="75" width="30" height="25" rx="3" fill="#818CF8"/>
                        <rect x="135" y="75" width="30" height="25" rx="3" fill="#818CF8"/>
                        <rect x="180" y="75" width="30" height="25" rx="3" fill="#818CF8"/>
                        <rect x="90" y="115" width="30" height="25" rx="3" fill="#818CF8"/>
                        <rect x="135" y="115" width="30" height="25" rx="3" fill="#818CF8"/>
                        <rect x="180" y="115" width="30" height="25" rx="3" fill="#818CF8"/>
                        <!-- Door -->
                        <rect x="130" y="155" width="40" height="45" rx="4" fill="#6366F1"/>
                        <!-- Car in front -->
                        <rect x="90" y="185" width="80" height="25" rx="6" fill="#3B82F6"/>
                        <path d="M105 185 L115 168 L155 168 L165 185 Z" fill="#2563EB"/>
                        <circle cx="105" cy="210" r="9" fill="#1E293B"/>
                        <circle cx="105" cy="210" r="5" fill="#64748B"/>
                        <circle cx="165" cy="210" r="9" fill="#1E293B"/>
                        <circle cx="165" cy="210" r="5" fill="#64748B"/>
                        <!-- Ground -->
                        <rect x="40" y="218" width="220" height="8" rx="4" fill="#E2E8F0"/>
                        <!-- KuPinjam label -->
                        <rect x="95" y="28" width="110" height="22" rx="5" fill="#3B82F6"/>
                        <text x="150" y="43" text-anchor="middle" font-family="sans-serif" font-size="11" font-weight="bold" fill="white">KuPinjam System</text>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════ FOOTER (Task 43) ═══ --}}
{{-- Task 43.1: fix link Support; Task 43.2: hapus sosmed placeholder --}}
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8 mb-8">
            <!-- Company Info — hapus ikon sosmed placeholder (Task 43.2) -->
            <div class="md:col-span-1">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="h-10 w-10 rounded-lg overflow-hidden bg-white flex items-center justify-center">
                        <img src="/images/logo/kupinjam.webp" alt="KuPinjam Logo" class="h-full w-full object-cover">
                    </div>
                    <div>
                        <span class="text-xl font-bold">KuPinjam</span>
                        <div class="text-sm text-gray-400">Vehicle Rental System</div>
                    </div>
                </div>
                <p class="text-gray-400 max-w-xs">
                    Solusi terdepan untuk peminjaman kendaraan perusahaan dengan sistem yang aman, efisien, dan mudah digunakan.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="#home"         class="text-gray-400 hover:text-white transition-colors duration-200">Home</a></li>
                    <li><a href="#features"     class="text-gray-400 hover:text-white transition-colors duration-200">Fitur</a></li>
                    <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors duration-200">Cara Kerja</a></li>
                    <li><a href="#about"        class="text-gray-400 hover:text-white transition-colors duration-200">Tentang</a></li>
                </ul>
            </div>

            <!-- Support — Task 43.1: link fungsional, hapus Privacy Policy & ToS -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors duration-200">Help Center</a></li>
                    <li><a href="mailto:admin@kupinjam.dev" class="text-gray-400 hover:text-white transition-colors duration-200">Hubungi Kami</a></li>
                    @guest
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Daftar</a></li>
                    @endguest
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} KuPinjam. All rights reserved.</p>
            <p class="text-gray-400 text-sm mt-2 md:mt-0">Made with ❤️ for better vehicle management</p>
        </div>
    </div>
</footer>

{{-- ═══════════════════════ Alpine Modal Panduan (Task 39.2) ═══ --}}
<div x-show="showGuide"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
     @keydown.escape.window="showGuide = false"
     style="display:none">
    <div x-show="showGuide"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden"
         @click.outside="showGuide = false">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Panduan Penggunaan KuPinjam</h3>
            <button @click="showGuide = false" class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            @php $panduanSteps = [
                ['color'=>'blue',  'num'=>1, 'title'=>'Registrasi Akun',    'desc'=>'Daftarkan akun baru atau masuk dengan akun yang sudah ada'],
                ['color'=>'green', 'num'=>2, 'title'=>'Akses Dashboard',    'desc'=>'Masuk ke dashboard untuk melihat kendaraan yang tersedia'],
                ['color'=>'purple','num'=>3, 'title'=>'Ajukan Peminjaman',  'desc'=>'Pilih kendaraan dan isi form peminjaman dengan lengkap'],
                ['color'=>'orange','num'=>4, 'title'=>'Tunggu Persetujuan', 'desc'=>'Admin akan memverifikasi dan menyetujui permohonan Anda'],
            ]; @endphp
            @foreach($panduanSteps as $ps)
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-7 h-7 bg-{{ $ps['color'] }}-100 rounded-full flex items-center justify-center text-{{ $ps['color'] }}-600 font-bold text-sm">{{ $ps['num'] }}</div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm">{{ $ps['title'] }}</h4>
                    <p class="text-gray-500 text-sm">{{ $ps['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
            <button @click="showGuide = false" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">Mengerti</button>
        </div>
    </div>
</div>

</div>{{-- end x-data Alpine wrapper --}}

{{-- ═══════════════════════════════════════════════ SCRIPTS ═══ --}}
{{-- Task 39.2: hapus SweetAlert2 CDN --}}
{{-- Task 40: Intersection Observer active nav, hamburger toggle, smooth scroll --}}
{{-- Task 42.1: counter animation --}}
<script>
(function () {
    // ── Task 40.2: Hamburger ↔ X toggle ──────────────────────────────────
    const btn        = document.getElementById('mobile-menu-button');
    const menu       = document.getElementById('mobile-menu');
    const iconHam    = document.getElementById('icon-hamburger');
    const iconClose  = document.getElementById('icon-close');

    function openMenu() {
        menu.classList.remove('hidden');
        iconHam.classList.add('hidden');
        iconClose.classList.remove('hidden');
    }
    function closeMenu() {
        menu.classList.add('hidden');
        iconHam.classList.remove('hidden');
        iconClose.classList.add('hidden');
    }

    btn.addEventListener('click', () => {
        menu.classList.contains('hidden') ? openMenu() : closeMenu();
    });

    // ── Task 40.3: Close menu + reset icon saat anchor link diklik ────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            closeMenu(); // selalu tutup, tanpa kondisi if(target)
        });
    });

    // ── Task 40.1: Intersection Observer — active nav highlight ──────────
    const sections   = document.querySelectorAll('section[id]');
    const navLinks   = document.querySelectorAll('.nav-link');
    const mobileLinks = document.querySelectorAll('.mobile-nav-link');

    const activeClasses   = ['text-blue-600', 'border-b-2', 'border-blue-600'];
    const inactiveClasses = ['text-gray-700'];

    function setActiveLink(id) {
        [...navLinks, ...mobileLinks].forEach(link => {
            const isActive = link.getAttribute('href') === '#' + id
                          || link.getAttribute('data-section') === id;
            activeClasses.forEach(c  => link.classList.toggle(c,  isActive));
            inactiveClasses.forEach(c => link.classList.toggle(c, !isActive));
        });
    }

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                setActiveLink(entry.target.id);
            }
        });
    }, { threshold: 0.4 });

    sections.forEach(s => observer.observe(s));

    // ── Navbar shadow on scroll ────────────────────────────────────────────
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('shadow-xl', window.scrollY > 100);
        navbar.classList.toggle('shadow-lg', window.scrollY <= 100);
    });

    // ── Task 42.1: Counter animation saat section #stats masuk viewport ───
    function animateCounter(el, target, duration) {
        const start    = performance.now();
        const from     = 0;
        const update   = (now) => {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            // ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(from + (target - from) * eased);
            if (progress < 1) requestAnimationFrame(update);
        };
        requestAnimationFrame(update);
    }

    let countersStarted = false;
    const statsSection  = document.getElementById('stats');

    if (statsSection) {
        const statsObserver = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting && !countersStarted) {
                countersStarted = true;
                document.querySelectorAll('.stat-item [data-target]').forEach(el => {
                    animateCounter(el, parseInt(el.dataset.target, 10), 1500);
                });
            }
        }, { threshold: 0.3 });

        statsObserver.observe(statsSection);
    }
})();
</script>
</body>
</html>
