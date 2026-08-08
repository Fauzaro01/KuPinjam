<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\RiwayatPengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DevelopmentSeeder extends Seeder
{
    /**
     * Seed data development yang realistis:
     * - 1 admin + 5 karyawan (password: password)
     * - 10 kendaraan (6 tersedia, 3 dipinjam, 1 perawatan)
     * - 5 peminjaman aktif (dipinjam)
     * - 3 peminjaman selesai
     * - 2 riwayat pengembalian pending (untuk demo badge notif admin)
     */
    public function run(): void
    {
        // ── 1. Users ──────────────────────────────────────────────────────────
        $admin = User::create([
            'id'       => Str::random(13),
            'username' => 'Admin KuPinjam',
            'email'    => 'admin@kupinjam.test',
            'no_telp'  => '081200000001',
            'password' => Hash::make('password'),
            'role'     => 'administrator',
        ]);

        $karyawans = collect([
            ['username' => 'Budi Santoso',   'email' => 'budi@kupinjam.test',   'no_telp' => '081200000002'],
            ['username' => 'Siti Rahayu',    'email' => 'siti@kupinjam.test',   'no_telp' => '081200000003'],
            ['username' => 'Andi Wijaya',    'email' => 'andi@kupinjam.test',   'no_telp' => '081200000004'],
            ['username' => 'Dewi Lestari',   'email' => 'dewi@kupinjam.test',   'no_telp' => '081200000005'],
            ['username' => 'Rizky Pratama',  'email' => 'rizky@kupinjam.test',  'no_telp' => '081200000006'],
        ])->map(fn ($data) => User::create(array_merge($data, [
            'id'       => Str::random(13),
            'password' => Hash::make('password'),
            'role'     => 'karyawan',
        ])));

        // ── 2. Kendaraan ─────────────────────────────────────────────────────
        $kendaraanData = [
            ['plat_nomor' => 'B 1001 KP', 'merk' => 'Toyota',     'model' => 'Avanza',  'tahun' => 2022, 'jenis_kendaraan' => 'mobil',  'status' => 'tersedia'],
            ['plat_nomor' => 'B 1002 KP', 'merk' => 'Honda',      'model' => 'Brio',    'tahun' => 2023, 'jenis_kendaraan' => 'mobil',  'status' => 'tersedia'],
            ['plat_nomor' => 'B 1003 KP', 'merk' => 'Daihatsu',   'model' => 'Xenia',   'tahun' => 2021, 'jenis_kendaraan' => 'mobil',  'status' => 'tersedia'],
            ['plat_nomor' => 'B 1004 KP', 'merk' => 'Suzuki',     'model' => 'Ertiga',  'tahun' => 2020, 'jenis_kendaraan' => 'mobil',  'status' => 'tersedia'],
            ['plat_nomor' => 'B 1005 KP', 'merk' => 'Mitsubishi', 'model' => 'Pajero',  'tahun' => 2022, 'jenis_kendaraan' => 'mobil',  'status' => 'tersedia'],
            ['plat_nomor' => 'B 1006 KP', 'merk' => 'Honda',      'model' => 'Jazz',    'tahun' => 2019, 'jenis_kendaraan' => 'mobil',  'status' => 'tersedia'],
            ['plat_nomor' => 'B 2001 KP', 'merk' => 'Honda',      'model' => 'Vario',   'tahun' => 2023, 'jenis_kendaraan' => 'motor',  'status' => 'dipinjam'],
            ['plat_nomor' => 'B 2002 KP', 'merk' => 'Yamaha',     'model' => 'NMax',    'tahun' => 2022, 'jenis_kendaraan' => 'motor',  'status' => 'dipinjam'],
            ['plat_nomor' => 'B 2003 KP', 'merk' => 'Honda',      'model' => 'Beat',    'tahun' => 2021, 'jenis_kendaraan' => 'motor',  'status' => 'dipinjam'],
            ['plat_nomor' => 'B 3001 KP', 'merk' => 'Toyota',     'model' => 'Innova',  'tahun' => 2018, 'jenis_kendaraan' => 'mobil',  'status' => 'perawatan'],
        ];

        $kendaraans = collect($kendaraanData)->map(fn ($data) => Kendaraan::create($data));

        // Referensi per status
        $kendaraanDipinjam = $kendaraans->where('status', 'dipinjam')->values();

        // ── 3. Peminjaman aktif (dipinjam) ────────────────────────────────────
        // Pasangkan karyawan dan kendaraan dipinjam
        $peminjamanAktif = [];
        foreach ([0, 1, 2] as $i) {
            $peminjamanAktif[] = Peminjaman::create([
                'user_id'           => $karyawans[$i]->id,
                'kendaraan_id'      => $kendaraanDipinjam[$i]->id,
                'tanggal_pinjam'    => Carbon::now()->subDays(rand(3, 10)),
                'tanggal_kembali'   => Carbon::now()->addDays(rand(2, 7)),
                'status_peminjaman' => 'dipinjam',
                'tujuan'            => ['Perjalanan dinas ke Bandung', 'Kunjungan klien Surabaya', 'Survey lokasi Bekasi'][$i],
                'keterangan'        => null,
            ]);
        }

        // ── 4. Peminjaman selesai ─────────────────────────────────────────────
        // Gunakan kendaraan tersedia untuk peminjaman lama yang sudah selesai
        $kendaraanTersedia = $kendaraans->where('status', 'tersedia')->values();

        foreach ([3, 4] as $idx => $karyawanIdx) {
            Peminjaman::create([
                'user_id'           => $karyawans[$karyawanIdx]->id,
                'kendaraan_id'      => $kendaraanTersedia[$idx]->id,
                'tanggal_pinjam'    => Carbon::now()->subDays(rand(20, 30)),
                'tanggal_kembali'   => Carbon::now()->subDays(rand(5, 15)),
                'status_peminjaman' => 'selesai',
                'tujuan'            => ['Rapat koordinasi wilayah', 'Pelatihan karyawan baru'][$idx],
                'keterangan'        => 'Selesai tanpa masalah.',
            ]);
        }

        // ── 5. RiwayatPengembalian pending (demo badge notif admin) ───────────
        foreach ([0, 1] as $i) {
            RiwayatPengembalian::create([
                'peminjaman_id'        => $peminjamanAktif[$i]->id,
                'catatan_pengembalian' => ['Kendaraan dalam kondisi baik.', 'Sudah dibersihkan sebelum dikembalikan.'][$i],
                'status'               => 'pending',
                'tanggal_pengajuan'    => Carbon::now()->subHours(rand(1, 12)),
                'tanggal_konfirmasi'   => null,
            ]);
        }

        $this->command->info('✔ DevelopmentSeeder selesai:');
        $this->command->info('  - 1 admin + 5 karyawan (password: password)');
        $this->command->info('  - 10 kendaraan (6 tersedia, 3 dipinjam, 1 perawatan)');
        $this->command->info('  - 3 peminjaman aktif + 2 selesai');
        $this->command->info('  - 2 pengajuan pengembalian pending');
        $this->command->info('');
        $this->command->info('  Login admin : admin@kupinjam.test / password');
        $this->command->info('  Login karyw : budi@kupinjam.test  / password');
    }
}
