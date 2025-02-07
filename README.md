# KuPinjam - Platform Peminjaman Kendaraan Industri untuk Karyawan

![KuPinjam](public/assets/static/images/logo/kupinjam.webp)

**KuPinjam** adalah sebuah platform berbasis web yang menyediakan layanan peminjaman kendaraan untuk kebutuhan industri. Dengan KuPinjam, perusahaan dapat memudahkan proses peminjaman kendaraan bagi para karyawannya. Data peminjaman akan tercatat dengan rapi dan terukur, sehingga memudahkan pengelolaan dan pemantauan penggunaan kendaraan perusahaan.

Platform ini bekerja sama dengan **PT Bintang Mas Karya Nusantara** untuk memberikan solusi peminjaman kendaraan dalam skala industri yang efisien dan transparan.

## Fitur Utama

- **Peminjaman Kendaraan**  
  Pengguna dapat meminjam kendaraan yang tersedia untuk digunakan dalam keperluan pekerjaan.

- **Pencatatan dan Pelaporan**  
  Semua transaksi peminjaman kendaraan tercatat secara otomatis, memudahkan pencatatan dan pelaporan.

- **Pengelolaan Kendaraan**  
  Admin dapat mengelola daftar kendaraan yang tersedia, termasuk menambahkan, mengedit, atau menghapus kendaraan.

- **Jadwal Peminjaman**  
  Menyediakan fitur untuk melihat jadwal peminjaman kendaraan dan menghindari peminjaman yang bertabrakan.

- **Dashboard Pengguna**  
  Setiap pengguna memiliki dashboard untuk melacak status peminjaman kendaraan yang sedang berlangsung.

## Cara Kerja

1. **Daftar dan Login**  
   Pengguna dan admin dapat mendaftar dan login untuk mengakses fitur peminjaman kendaraan.

2. **Pilih Kendaraan**  
   Pengguna memilih kendaraan yang dibutuhkan untuk peminjaman dari daftar kendaraan yang tersedia.

3. **Proses Peminjaman**  
   Setelah memilih kendaraan, pengguna mengisi form peminjaman yang mencakup tanggal penggunaan dan tujuan peminjaman.

4. **Pencatatan Otomatis**  
   Semua transaksi peminjaman kendaraan akan tercatat secara otomatis dalam sistem, tanpa perlu verifikasi admin.

5. **Pengembalian Kendaraan**  
   Setelah selesai menggunakan kendaraan, pengguna mengembalikannya dan status peminjaman akan tercatat selesai secara otomatis.

## Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Bootstrap 5.2
- **Database**: MySQL
- **Dependency Management**: Composer

## Instalasi

### Persyaratan Sistem

- PHP 8.0 atau lebih baru
- Composer (untuk mengelola dependensi PHP)
- MySQL
- Node.js dan npm (untuk pengelolaan frontend)

### Langkah-langkah Instalasi

1. **Clone Repository**

   ```bash
   git clone https://github.com/username/KuPinjam.git
   cd KuPinjam
   ```
2. **Instalasi Dependensi Backend**
    Instal dependensi backend dengan Composer:
    ```bash
    composer install
    ```
3. **Konfigurasi file .env**

    Salin `.env.example` dan sesuaikan konfigurasi database di file .env pada proyek ini.
    ```bash
    cp .env.example .env
    ```

4. **Setup Aplikasi && Migrasi databse**

    Jalankan migrasi database untuk menyiapkan tabel yang diperlukan:
    ```bash
    php artisan key:generate
    php artisan migrate
    ```
5. **Mulai server backend dan frontend**
    ```bash
    php artisan serve
    ```

   Aplikasi dapat diakses di http://localhost:8000.
   
   #### Catatan Tambahan: 
   - Jika ingin menggunakan pada semua host bisa tambahkan ` --host 0.0.0.0 `
   - Jika ingin mengganti port bisa tambahkan 
   ` --port [port] `