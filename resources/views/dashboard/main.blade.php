<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KuPinjam</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/png">

    <link rel="stylesheet" crossorigin href="/assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="/assets/compiled/css/iconly.css">
    <link rel="stylesheet" href="/assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" crossorigin="" href="/assets/compiled/css/table-datatable.css">
</head>

<body>
    <script src="/assets/static/js/initTheme.js"></script>
    <div id="app">
        @include("layouts/sidebar-dashboard")
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>Dashboard</h3>
            </div>
            <div class="page-content">
                @if ($message = Session::get('success'))
                <div class="alert alert-light-primary color-success">
                    <i class="bi bi-person-check"></i>
                    {{ $message }}
                </div>
                @endif
                <section class="row">
                    <div class="col-12 col-lg-9">
                        @if(Auth::user()->hasRole('administrator'))
                        <div class="row">
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div
                                                class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon blue mb-2">
                                                    <i class="iconly-boldProfile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Administrator</h6>
                                                <h6 class="font-extrabold mb-0">{{$users->where('role',
                                                    'administrator')->count()}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div
                                                class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon green mb-2">
                                                    <i class="iconly-boldAdd-User"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Karyawan</h6>
                                                <h6 class="font-extrabold mb-0">{{$users->where('role',
                                                    'karyawan')->count()}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div
                                                class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon red mb-2">
                                                    <i class="iconly-boldCategory"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Mobil</h6>
                                                <h6 class="font-extrabold mb-0">{{$kendaraan->where('jenis_kendaraan',
                                                    'mobil')->count()}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div
                                                class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon red mb-2">
                                                    <i class="iconly-boldCategory"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Motor</h6>
                                                <h6 class="font-extrabold mb-0">{{$kendaraan->where('jenis_kendaraan',
                                                    'motor')->count()}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                @if(Auth::user()->hasRole('administrator'))
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Daftar Peminjaman Terbaru</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Nama Peminjam</th>
                                                        <th>Jenis Kendaraan</th>
                                                        <th>Plat Nomor</th>
                                                        <th>Tanggal Pinjam</th>
                                                        <th>Tanggal Kembali</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($peminjamans as $peminjaman)
                                                    <tr>
                                                        <td>{{$peminjaman->id}}</td>
                                                        <td>{{$peminjaman->user->username}}</td>
                                                        <td>
                                                            @if($peminjaman->kendaraan->jenis_kendaraan == 'motor')
                                                            <span class="badge bg-light-primary">
                                                                <i class="bi bi-bicycle me-2"></i>
                                                                Motor
                                                            </span>
                                                            @elseif($peminjaman->kendaraan->jenis_kendaraan == 'mobil')
                                                            <span class="badge bg-light-info">
                                                                <i class="bi bi-car-front me-2"></i>
                                                                Mobil
                                                            </span>
                                                            @endif
                                                        </td>
                                                        <td>{{$peminjaman->kendaraan->plat_nomor}}</td>
                                                        <td>{{$peminjaman->tanggal_pinjam}}</td>
                                                        <td>{{$peminjaman->tanggal_kembali}}</td>
                                                        <td>
                                                            @if($peminjaman->status_peminjaman == 'selesai')
                                                            <span class="badge bg-light-success">
                                                                <i class="bi bi-check2 me-2"></i>
                                                                Selesai
                                                            </span>
                                                            @elseif($peminjaman->status_peminjaman == 'dipinjam')
                                                            <span class="badge bg-light-warning">
                                                                <i class="bi bi-cone-striped me-2"></i>
                                                                DiPinjam
                                                            </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Cara Meminjam Mobil</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion accordion-flush" id="accordionFlushExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Pergi Halaman Kendaraan
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                                                    <div class="accordion-body">Untuk mulai meminjam mobil, silakan tekan tombol "<code>Kendaraan</code>" yang terdapat di sebelah kiri atau pada sidebar platform.</div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingTwo">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                                        Pilih "Pinjam Kendaraan"
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample" style="">
                                                    <div class="accordion-body">Setelah itu, klik tombol  "<code>Pinjam Kendaraan</code>" yang muncul setelah memilih mobil yang ingin Anda sewa.</div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingThree">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                                        Tentukan Rentang Waktu Peminjaman & Tujuan peminjaman
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample" style="">
                                                    <div class="accordion-body">Pilih <code>tanggal mulai</code> dan <code>tanggal akhir</code> untuk peminjaman Anda dengan mudah menggunakan kalender yang tersedia. Jika perlu, masukkan tujuan perjalanan Anda di kolom yang disediakan.</div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingThree">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                                        Kirim Permintaan
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample" style="">
                                                    <div class="accordion-body">Setelah semua informasi diisi, tekan tombol "<code>Kirim</code>" untuk mengonfirmasi permintaan peminjaman. Permintaan peminjaman Anda sudah terkirim!</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-body py-4 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <img src="/assets/compiled/jpg/{{rand(1,3)}}.jpg" alt="/assets/compiled/jpg/1.jpg">
                                    </div>
                                    <div class="ms-3 name">
                                        <h5 class="font-bold">{{Auth::user()->username}}</h5>
                                        <h6 class="text-muted mb-0">{{Auth::user()->role}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            @include('layouts/footer-dashboard')
        </div>
    </div>
    <script src="/assets/static/js/components/dark.js"></script>
    <script src="/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <script src="/assets/compiled/js/app.js"></script>
    <script src="/assets/static/js/pages/dashboard.js"></script>

</body>

</html>