<div align="center">

# KuPinjam

**Sistem Manajemen Peminjaman Kendaraan Perusahaan**

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white)](https://sqlite.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)

<br/>

*Platform web untuk mengelola peminjaman kendaraan perusahaan — dari pengajuan, konfirmasi, hingga pengembalian.*

</div>

---

## Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur](#fitur)
- [Tech Stack](#tech-stack)
- [Arsitektur](#arsitektur)
- [Instalasi](#instalasi)
- [Penggunaan](#penggunaan)
- [Testing](#testing)
- [Struktur Proyek](#struktur-proyek)
- [Kontribusi](#kontribusi)

---

## Tentang Proyek

**KuPinjam** adalah aplikasi web berbasis Laravel yang memudahkan perusahaan mengelola armada kendaraan untuk keperluan operasional karyawan. Sistem ini mengotomasi alur peminjaman dari awal hingga pengembalian — menggantikan pencatatan manual yang rawan kesalahan.

### Alur Utama

```
Karyawan memilih kendaraan  →  Mengisi form peminjaman  →  Menggunakan kendaraan
        ↓
Karyawan mengajukan pengembalian  →  Admin konfirmasi  →  Kendaraan kembali tersedia
```

---

## Fitur

### Karyawan
- 🚗 **Browse kendaraan tersedia** dengan modal pinjam langsung dari halaman daftar
- 📋 **Riwayat peminjaman** personal dengan status real-time
- 🔄 **Ajukan pengembalian** dengan catatan opsional
- 👤 **Kelola profil** — username, nomor telepon, dan foto avatar
- 🔒 **Ganti password** via halaman keamanan akun

### Administrator
- 📊 **Dashboard informatif** — statistik kendaraan, peminjaman aktif, pengembalian pending
- 🚘 **CRUD kendaraan** — tambah, edit, hapus, dan monitor status armada
- 📝 **Kelola peminjaman** — buat peminjaman untuk karyawan, edit, dan hapus
- ✅ **Konfirmasi / tolak pengembalian** dengan filter status tab (pending / dikonfirmasi / ditolak)
- 👥 **Manajemen user** — tambah, edit, hapus, dan bulk import via CSV
- 📥 **Export laporan peminjaman** ke CSV dengan sekali klik

### Sistem
- 🌙 **Dark mode** dengan preferensi tersimpan di localStorage (anti-FOUC)
- 📱 **Responsive** — sidebar collapsible di mobile
- 🔐 **Role-based authorization** via Laravel Policy (karyawan / administrator)
- ⚡ **Rate limiting** pada endpoint login dan register
- 🛡️ **Form validation** via FormRequest classes

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11, PHP 8.2+ |
| Frontend | Tailwind CSS 3.4, Alpine.js 3.x |
| Build Tool | Vite 6 |
| Database | SQLite (default) / MySQL |
| Testing | PHPUnit 11 |
| Auth & Authz | Laravel Session Auth + Policy |

---

## Arsitektur

Proyek mengikuti pola **Service Layer** di atas MVC Laravel:

```
HTTP Request
    └── FormRequest (validasi)
        └── Controller (thin — hanya orchestrate)
            └── Service (business logic)
                └── Eloquent Model
```

**Komponen utama:**

| Komponen | Keterangan |
|----------|-----------|
| `KendaraanService` | Create, update, delete, set status kendaraan |
| `PeminjamanService` | Validasi ketersediaan, buat/update/hapus peminjaman |
| `PengembalianService` | Ajukan, konfirmasi (atomik via DB::transaction), tolak |
| `UserService` | CRUD user, bulk import CSV, update profil & avatar |
| `*Policy` | Otorisasi berbasis role untuk setiap model |

---

## Instalasi

### Prasyarat

- PHP **8.2+** (dengan extension `pdo_sqlite` aktif)
- Composer
- Node.js 18+ & npm
- Git

### Langkah Instalasi

```bash
# 1. Clone repo
git clone https://github.com/Fauzaro01/KuPinjam.git
cd KuPinjam

# 2. Install PHP dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Buat database SQLite & jalankan migrasi
touch database/database.sqlite
php artisan migrate

# 5. (Opsional) Seed data development
php artisan db:seed

# 6. Install Node dependencies & build assets
npm install
npm run build

# 7. Buat symlink storage untuk avatar
php artisan storage:link

# 8. Jalankan server
php artisan serve
```

Akses di **http://localhost:8000**

### Menggunakan MySQL

Ubah konfigurasi di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kupinjam
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan untuk XAMPP**: Pastikan extension `pdo_sqlite` aktif di `php.ini` jika menggunakan SQLite. Uncomment baris `;extension=pdo_sqlite`.

---

## Penggunaan

### Akun Default (setelah `db:seed`)

| Role | Email | Password |
|------|-------|----------|
| Administrator | `admin@kupinjam.test` | `password` |
| Karyawan | `budi@kupinjam.test` | `password` |

### Alur Peminjaman Kendaraan

1. Login sebagai **karyawan**
2. Buka menu **Kendaraan**
3. Klik tombol **Pinjam** pada kendaraan yang tersedia
4. Isi form di modal (tanggal pinjam, tanggal kembali, tujuan)
5. Submit — kendaraan langsung berubah status menjadi *Dipinjam*

### Alur Pengembalian

1. Buka menu **Peminjaman**
2. Klik **Kembalikan** pada peminjaman aktif
3. Admin login dan buka menu **Pengembalian**
4. Klik **Konfirmasi** — kendaraan kembali menjadi *Tersedia*

### Bulk Import User (Admin)

1. Download template CSV dari **Manajemen User → Import CSV**
2. Isi data dengan kolom: `username, email, no_telp, password`
3. Upload file — baris duplikat dilewati otomatis dengan laporan ringkasan

---

## Testing

```bash
# Jalankan semua test
php artisan test

# Jalankan test spesifik
php artisan test --filter PeminjamanServiceTest
php artisan test --filter PengembalianTest

# Test dengan coverage (membutuhkan Xdebug)
php artisan test --coverage
```

**Coverage saat ini:** 62 tests, 133 assertions

| Test Suite | Tests | Keterangan |
|-----------|-------|-----------|
| `PeminjamanServiceTest` | 11 | Unit — logika service layer |
| `PengembalianServiceTest` | 9 | Unit — atomisitas & idempoten |
| `LoginTest` | 7 | Feature — autentikasi |
| `RegisterTest` | 7 | Feature — registrasi & duplikat |
| `AuthorizationTest` | 10 | Feature — role-based access |
| `PeminjamanTest` | 6 | Feature — alur peminjaman |
| `PengembalianTest` | 6 | Feature — alur pengembalian |
| `BulkImportTest` | 5 | Feature — CSV import |

---

## Struktur Proyek

```
KuPinjam/
├── app/
│   ├── Exceptions/          # KendaraanTidakTersediaException
│   ├── Http/
│   │   ├── Controllers/     # Thin controllers
│   │   └── Requests/        # FormRequest validation classes
│   ├── Models/              # Eloquent models
│   ├── Policies/            # Authorization policies
│   └── Services/            # Business logic layer
├── database/
│   ├── factories/           # Model factories untuk testing
│   ├── migrations/
│   └── seeders/             # DevelopmentSeeder
├── resources/
│   ├── css/app.css          # Tailwind + DataTables override
│   ├── js/                  # Alpine.js, darkmode, datatables, sidebar
│   └── views/
│       ├── admin/           # Views khusus administrator
│       ├── auth/            # Login, register, keamanan
│       ├── components/      # Alert component
│       ├── dashboard/       # Dashboard admin & karyawan
│       ├── errors/          # Custom 403, 404, 500 pages
│       ├── kendaraan/       # CRUD kendaraan
│       ├── layouts/         # Default dashboard layout + sidebar
│       ├── peminjaman/      # CRUD peminjaman + modal pinjam
│       ├── pengembalian/    # Kelola pengembalian (admin)
│       └── profile/         # Halaman profil user
├── routes/web.php           # Semua route dengan middleware
└── tests/
    ├── Feature/             # HTTP-level feature tests
    └── Unit/                # Service layer unit tests
```

---

## Kontribusi

Pull request dan issue sangat disambut. Untuk perubahan besar, buka issue terlebih dahulu untuk mendiskusikan apa yang ingin diubah.

```bash
# Buat branch baru
git checkout -b feat/nama-fitur

# Pastikan test pass sebelum PR
php artisan test
npm run build
```

---

<div align="center">

Dibuat dengan ❤️ menggunakan Laravel 11 + Tailwind CSS

</div>
