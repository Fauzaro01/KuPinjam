<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KuPinjam</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/png">

    <link rel="stylesheet" crossorigin href="assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/app-dark.css">
    <link rel="stylesheet" crossorigin href="assets/compiled/css/iconly.css">
    <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" crossorigin="" href="assets/compiled/css/table-datatable.css">
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
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
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Profile Visit</h4>
                                    </div>
                                    <div class="card-body">
                                        <!-- <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>NAME</th>
                                                        <th>RATE</th>
                                                        <th>SKILL</th>
                                                        <th>TYPE</th>
                                                        <th>LOCATION</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-bold-500">Michael Right</td>
                                                        <td>$15/hr</td>
                                                        <td class="text-bold-500">UI/UX</td>
                                                        <td>Remote</td>
                                                        <td>Austin,Taxes</td>
                                                        <td><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                                    <path
                                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                                    </path>
                                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                                </svg></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-bold-500">Morgan Vanblum</td>
                                                        <td>$13/hr</td>
                                                        <td class="text-bold-500">Graphic concepts</td>
                                                        <td>Remote</td>
                                                        <td>Shangai,China</td>
                                                        <td><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                                    <path
                                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                                    </path>
                                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                                </svg></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-bold-500">Tiffani Blogz</td>
                                                        <td>$15/hr</td>
                                                        <td class="text-bold-500">Animation</td>
                                                        <td>Remote</td>
                                                        <td>Austin,Texas</td>
                                                        <td><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                                    <path
                                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                                    </path>
                                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                                </svg></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-bold-500">Ashley Boul</td>
                                                        <td>$15/hr</td>
                                                        <td class="text-bold-500">Animation</td>
                                                        <td>Remote</td>
                                                        <td>Austin,Texas</td>
                                                        <td><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                                    <path
                                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                                    </path>
                                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                                </svg></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-bold-500">Mikkey Mice</td>
                                                        <td>$15/hr</td>
                                                        <td class="text-bold-500">Animation</td>
                                                        <td>Remote</td>
                                                        <td>Austin,Texas</td>
                                                        <td><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                                    <path
                                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                                    </path>
                                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                                </svg></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-body py-4 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <img src="./assets/compiled/jpg/1.jpg" alt="Face 1">
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
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>


    <script src="assets/compiled/js/app.js"></script>

    <!-- <script src="assets/static/js/pages/simple-datatables.js"></script>
    <script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script> -->
    <script src="assets/static/js/pages/dashboard.js"></script>

</body>

</html>