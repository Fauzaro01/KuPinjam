<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama | KuPinjam</title>
    <link rel="shortcut icon" href="https://bintangmas-engineering.com/wp-content/uploads/2025/01/cropped-site-logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
        </a>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="#" class="nav-link px-2 link-secondary">Home</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Panduan</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">About</a></li>
        </ul>

        <div class="col-md-3 text-end">
            @guest
            <a href="{{route('login')}}" class="btn btn-sm btn-dark me-2">Masuk</a>
            <a href="{{route('register')}}" class="btn btn-sm btn-outline-dark me-2">Daftar</a>
            @else
            
            @endguest
        </div>
        </header>
    </div>
    <div class="container py-6">
        <div class="row flex-lg-row-reverse align-items-center g-5">
            <div class="col-10 mx-auto col-sm-8 col-lg-6">
                <img src="https://bintangmas-engineering.com/wp-content/uploads/2025/01/cropped-site-logo.png" class="d-block mx-lg-auto img-fluid" alt="" loading="lazy">
            </div>
            <div class="col-lg-6">
                <div class="lc-block mb-3">
                    <div editable="rich">
                        <h2 class="fw-bold display-5">Sistem Peminjaman Kendaraan</h2>
                    </div>
                </div>

                <div class="lc-block mb-3">
                    <div editable="rich">
                        <p class="lead">Melalui sistem ini, Anda dapat meinjam kendaraan perusahaan dengan cepat, melacak status peminjaman, serta memastikan kendaraan yang digunakan selalu dalam kondisi prima. Dirancang khusus agar memberikan fleksibilitas dan kemudahan dalam mendukung kegiatan dinas dan tugas sehari-hari.
                        </p>
                    </div>
                </div>

                <div class="lc-block d-grid gap-2 d-md-flex justify-content-md-start">
                  <a class="btn btn-dark px-4 me-md-2" href="#" role="button">Panduan</a>
                  <a class="btn btn-outline-secondary px-4" href="{{route('login')}}" role="button">Mulai Meminjam</a>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>