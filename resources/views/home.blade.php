<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama | KuPinjam</title>
    <link rel="shortcut icon" href="https://bintangmas-engineering.com/wp-content/uploads/2025/01/cropped-site-logo.png"
        type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@heroicons/heroicons@2.0.0/outline/index.css">
</head>

<body class="font-sans antialiased bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/90 backdrop-blur-md shadow-lg sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="https://bintangmas-engineering.com/wp-content/uploads/2025/01/cropped-site-logo.png" 
                         alt="KuPinjam Logo" class="h-10 w-10 rounded-lg shadow-md">
                    <div class="flex flex-col">
                        <span class="text-xl font-bold text-gray-900">KuPinjam</span>
                        <span class="text-xs text-gray-500">Vehicle Rental System</span>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#home" class="text-blue-600 hover:text-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 border-b-2 border-blue-600">
                            Home
                        </a>
                        <a href="#features" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Fitur
                        </a>
                        <a href="#how-it-works" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Cara Kerja
                        </a>
                        <a href="#about" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Tentang
                        </a>
                    </div>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @guest
                        <a href="{{route('login')}}" 
                           class="text-gray-700 hover:text-blue-600 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-blue-50">
                            Masuk
                        </a>
                        <a href="{{route('register')}}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Daftar
                        </a>
                    @else
                        <a href="{{route('dashboard')}}" 
                           class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Dashboard
                        </a>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" type="button" 
                            class="text-gray-700 hover:text-blue-600 inline-flex items-center justify-center p-2 rounded-md transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#home" class="text-blue-600 block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="#features" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Fitur</a>
                <a href="#how-it-works" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Cara Kerja</a>
                <a href="#about" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Tentang</a>
                
                <!-- Mobile Auth Buttons -->
                <div class="pt-4 border-t border-gray-200">
                    @guest
                        <a href="{{route('login')}}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg text-base font-medium mb-2">
                            Masuk
                        </a>
                        <a href="{{route('register')}}" class="block w-full text-center border-2 border-blue-600 text-blue-600 px-4 py-2 rounded-lg text-base font-medium">
                            Daftar
                        </a>
                    @else
                        <a href="{{route('dashboard')}}" class="block w-full text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg text-base font-medium">
                            Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-purple-50">
            <div class="absolute inset-0 bg-grid-slate-100 opacity-50"></div>
        </div>
        
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
                        <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Dengan Mudah
                        </span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Solusi terdepan untuk peminjaman kendaraan perusahaan. Kelola, pantau, dan gunakan kendaraan dengan sistem yang aman, efisien, dan mudah digunakan.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button 
                            onclick="showGuideModal()"
                            class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 group">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Panduan Penggunaan
                        </button>
                        
                        <a href="{{route('login')}}" 
                           class="inline-flex items-center justify-center px-8 py-4 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold rounded-lg transition-all duration-200 group">
                            <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Mulai Meminjam
                        </a>
                    </div>

                    <!-- Stats -->
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
                
                <!-- Hero Image/Illustration -->
                <div class="relative">
                    <div class="aspect-w-3 aspect-h-4 lg:aspect-w-1 lg:aspect-h-1">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-500 rounded-3xl transform rotate-3 shadow-2xl"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl p-8 transform -rotate-1">
                            <img src="https://bintangmas-engineering.com/wp-content/uploads/2025/01/cropped-site-logo.png" 
                                 alt="KuPinjam Vehicle System" 
                                 class="w-full h-full object-contain filter drop-shadow-lg">
                        </div>
                    </div>
                    
                    <!-- Floating Cards -->
                    <div class="absolute -top-8 -left-8 bg-white rounded-xl shadow-lg p-4 animate-float">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Verified System</div>
                                <div class="text-xs text-gray-500">100% Secure</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-8 -right-8 bg-white rounded-xl shadow-lg p-4 animate-float-delayed">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Fast Process</div>
                                <div class="text-xs text-gray-500">Quick & Easy</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Mengapa Memilih <span class="text-blue-600">KuPinjam?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Sistem peminjaman kendaraan modern dengan fitur-fitur canggih untuk memudahkan aktivitas Anda
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Peminjaman Real-time</h3>
                    <p class="text-gray-600">Proses peminjaman yang cepat dan mudah dengan update status secara real-time untuk transparansi penuh.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="group bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Keamanan Terjamin</h3>
                    <p class="text-gray-600">Sistem keamanan berlapis untuk melindungi data pribadi dan memastikan kendaraan dalam kondisi aman.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="group bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Laporan & Analitik</h3>
                    <p class="text-gray-600">Dashboard komprehensif dengan laporan detail dan analitik penggunaan kendaraan untuk manajemen yang optimal.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="group bg-gradient-to-br from-orange-50 to-orange-100 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-orange-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Mobile Friendly</h3>
                    <p class="text-gray-600">Akses sistem dari mana saja dengan tampilan responsif yang optimal di semua perangkat mobile dan desktop.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="group bg-gradient-to-br from-red-50 to-red-100 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Support 24/7</h3>
                    <p class="text-gray-600">Dukungan teknis dan bantuan pengguna tersedia 24 jam untuk memastikan kelancaran operasional sistem.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="group bg-gradient-to-br from-indigo-50 to-indigo-100 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">User Experience</h3>
                    <p class="text-gray-600">Interface yang intuitif dan user-friendly dirancang khusus untuk memberikan pengalaman terbaik bagi pengguna.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Cara <span class="text-blue-600">Kerja</span> Sistem
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Proses peminjaman yang mudah dan efisien dalam 4 langkah sederhana
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto shadow-lg group-hover:shadow-xl transform group-hover:scale-110 transition-all duration-300">
                            <span class="text-2xl font-bold text-white">1</span>
                        </div>
                        <div class="absolute top-10 left-1/2 w-full h-0.5 bg-gradient-to-r from-blue-600 to-transparent transform translate-x-8 hidden lg:block"></div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Registrasi Akun</h3>
                    <p class="text-gray-600">Daftarkan akun Anda dengan informasi yang valid untuk mengakses sistem peminjaman kendaraan.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto shadow-lg group-hover:shadow-xl transform group-hover:scale-110 transition-all duration-300">
                            <span class="text-2xl font-bold text-white">2</span>
                        </div>
                        <div class="absolute top-10 left-1/2 w-full h-0.5 bg-gradient-to-r from-green-600 to-transparent transform translate-x-8 hidden lg:block"></div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Pilih Kendaraan</h3>
                    <p class="text-gray-600">Browse dan pilih kendaraan yang sesuai dengan kebutuhan Anda dari daftar yang tersedia.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-20 h-20 bg-purple-600 rounded-full flex items-center justify-center mx-auto shadow-lg group-hover:shadow-xl transform group-hover:scale-110 transition-all duration-300">
                            <span class="text-2xl font-bold text-white">3</span>
                        </div>
                        <div class="absolute top-10 left-1/2 w-full h-0.5 bg-gradient-to-r from-purple-600 to-transparent transform translate-x-8 hidden lg:block"></div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Submit Permintaan</h3>
                    <p class="text-gray-600">Isi formulir peminjaman dengan detail keperluan dan waktu penggunaan kendaraan.</p>
                </div>
                
                <!-- Step 4 -->
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-20 h-20 bg-orange-600 rounded-full flex items-center justify-center mx-auto shadow-lg group-hover:shadow-xl transform group-hover:scale-110 transition-all duration-300">
                            <span class="text-2xl font-bold text-white">4</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Gunakan Kendaraan</h3>
                    <p class="text-gray-600">Setelah disetujui, ambil kendaraan dan gunakan sesuai dengan ketentuan yang berlaku.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                <div class="mb-12 lg:mb-0">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        Tentang <span class="text-blue-600">KuPinjam</span>
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        KuPinjam adalah solusi digital terdepan untuk manajemen peminjaman kendaraan perusahaan. 
                        Kami menghadirkan sistem yang modern, aman, dan efisien untuk memudahkan proses peminjaman 
                        kendaraan dalam lingkungan kerja.
                    </p>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Dengan teknologi terkini dan antarmuka yang user-friendly, KuPinjam membantu perusahaan 
                        mengoptimalkan penggunaan armada kendaraan mereka sambil memastikan transparansi dan akuntabilitas 
                        dalam setiap transaksi.
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
                
                <div class="relative">
                    <div class="aspect-w-1 aspect-h-1">
                        <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-3xl p-8">
                            <img src="https://bintangmas-engineering.com/wp-content/uploads/2025/01/cropped-site-logo.png" 
                                 alt="About KuPinjam" 
                                 class="w-full h-full object-contain filter drop-shadow-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="https://bintangmas-engineering.com/wp-content/uploads/2025/01/cropped-site-logo.png" 
                             alt="KuPinjam Logo" class="h-10 w-10 rounded-lg">
                        <div>
                            <span class="text-xl font-bold">KuPinjam</span>
                            <div class="text-sm text-gray-400">Vehicle Rental System</div>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4 max-w-md">
                        Solusi terdepan untuk peminjaman kendaraan perusahaan dengan sistem yang aman, efisien, dan mudah digunakan.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-400 hover:text-white transition-colors duration-200">Home</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors duration-200">Fitur</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors duration-200">Cara Kerja</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-white transition-colors duration-200">Tentang</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; 2025 KuPinjam. All rights reserved.
                </p>
                <p class="text-gray-400 text-sm mt-2 md:mt-0">
                    Made with ❤️ for better vehicle management
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="/assets/extensions/sweetalert2/sweetalert2.all.js"></script>
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    mobileMenu.classList.add('hidden');
                }
            });
        });

        // Show guide modal function
        function showGuideModal() {
            Swal.fire({
                title: 'Panduan Penggunaan KuPinjam',
                html: `
                    <div class="text-left space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">1</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Registrasi Akun</h4>
                                <p class="text-gray-600 text-sm">Daftarkan akun baru atau masuk dengan akun yang sudah ada</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-bold text-sm">2</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Akses Dashboard</h4>
                                <p class="text-gray-600 text-sm">Masuk ke dashboard untuk melihat kendaraan yang tersedia</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-sm">3</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Ajukan Peminjaman</h4>
                                <p class="text-gray-600 text-sm">Pilih kendaraan dan isi form peminjaman dengan lengkap</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm">4</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Tunggu Persetujuan</h4>
                                <p class="text-gray-600 text-sm">Admin akan memverifikasi dan menyetujui permohonan Anda</p>
                            </div>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#2563eb',
                width: '600px',
                customClass: {
                    popup: 'rounded-xl',
                    title: 'text-xl font-bold',
                    confirmButton: 'px-6 py-2 rounded-lg font-medium'
                }
            });
        }

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('nav');
            if (window.scrollY > 100) {
                navbar.classList.add('shadow-xl');
                navbar.classList.remove('shadow-lg');
            } else {
                navbar.classList.remove('shadow-xl');
                navbar.classList.add('shadow-lg');
            }
        });

        // Add custom CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
            .animate-float-delayed {
                animation: float 3s ease-in-out infinite;
                animation-delay: 1.5s;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>