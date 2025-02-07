<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KuPinjam @yield('title')</title>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    @yield('head-css')
    <link rel="stylesheet" crossorigin="" href="/assets/compiled/css/app.css">
    <link rel="stylesheet" crossorigin="" href="/assets/compiled/css/app-dark.css">
</head>

<body class="light">
    <script src="/assets/static/js/initTheme.js"></script>
    <div id="app">
        @include('layouts/sidebar-dashboard')
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @yield('content')

            @include('layouts/footer-dashboard')
        </div>
    </div>
    <script src="/assets/static/js/components/dark.js"></script>
    <script src="/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/compiled/js/app.js"></script>
</body>

</html>