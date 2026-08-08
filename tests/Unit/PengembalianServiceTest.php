<?php

namespace Tests\Unit;

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\RiwayatPengembalian;
use App\Models\User;
use App\Services\PengembalianService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PengembalianServiceTest extends TestCase
{
    use RefreshDatabase;

    private PengembalianService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PengembalianService::class);
    }

    // ── Helper ─────────────────────────────────────────────────────────────

    /**
     * Buat skenario peminjaman aktif lengkap:
     * user karyawan → kendaraan dipinjam → peminjaman dipinjam
     */
    private function buatSkenaroPeminjamanAktif(): array
    {
        $user      = User::factory()->create(['role' => 'karyawan']);
        $kendaraan = Kendaraan::factory()->create(['status' => 'dipinjam']);
        $peminjaman = Peminjaman::factory()->create([
            'user_id'           => $user->id,
            'kendaraan_id'      => $kendaraan->id,
            'status_peminjaman' => 'dipinjam',
        ]);

        return compact('user', 'kendaraan', 'peminjaman');
    }

    // ── 25.2.1: ajukanPengembalian — membuat record pending ───────────────

    #[Test]
    public function ajukanPengembalian_membuat_riwayat_dengan_status_pending(): void
    {
        ['peminjaman' => $p] = $this->buatSkenaroPeminjamanAktif();

        $riwayat = $this->service->ajukanPengembalian($p, 'Selesai digunakan');

        $this->assertSame('pending', $riwayat->status);
        $this->assertSame($p->id, $riwayat->peminjaman_id);
        $this->assertDatabaseHas('riwayat_pengembalians', [
            'peminjaman_id' => $p->id,
            'status'        => 'pending',
        ]);
    }

    // ── 25.2.2: ajukanPengembalian — idempoten (tidak bisa duplikat pending)

    #[Test]
    public function ajukanPengembalian_throws_exception_jika_sudah_ada_pending(): void
    {
        $this->expectException(\RuntimeException::class);

        ['peminjaman' => $p] = $this->buatSkenaroPeminjamanAktif();

        $this->service->ajukanPengembalian($p, 'Pertama');
        $this->service->ajukanPengembalian($p, 'Kedua'); // harus throw
    }

    #[Test]
    public function ajukanPengembalian_tidak_membuat_record_duplikat(): void
    {
        ['peminjaman' => $p] = $this->buatSkenaroPeminjamanAktif();

        $this->service->ajukanPengembalian($p, 'Pertama');

        try {
            $this->service->ajukanPengembalian($p, 'Kedua');
        } catch (\RuntimeException) {
            // expected
        }

        $this->assertDatabaseCount('riwayat_pengembalians', 1);
    }

    // ── 25.2.3: konfirmasiPengembalian — atomik ───────────────────────────

    #[Test]
    public function konfirmasiPengembalian_mengubah_ketiga_status_serentak(): void
    {
        ['peminjaman' => $p, 'kendaraan' => $k] = $this->buatSkenaroPeminjamanAktif();

        $riwayat = RiwayatPengembalian::factory()->create([
            'peminjaman_id' => $p->id,
            'status'        => 'pending',
        ]);

        $this->service->konfirmasiPengembalian($riwayat);

        $this->assertSame('dikonfirmasi', $riwayat->fresh()->status);
        $this->assertSame('selesai',      $p->fresh()->status_peminjaman);
        $this->assertSame('tersedia',     $k->fresh()->status);
    }

    #[Test]
    public function konfirmasiPengembalian_mengisi_tanggal_konfirmasi(): void
    {
        ['peminjaman' => $p] = $this->buatSkenaroPeminjamanAktif();

        $riwayat = RiwayatPengembalian::factory()->create([
            'peminjaman_id' => $p->id,
            'status'        => 'pending',
        ]);

        $this->service->konfirmasiPengembalian($riwayat);

        $this->assertNotNull($riwayat->fresh()->tanggal_konfirmasi);
    }

    // ── 25.2.4: tolakPengembalian — hanya riwayat berubah ────────────────

    #[Test]
    public function tolakPengembalian_hanya_mengubah_status_riwayat(): void
    {
        ['peminjaman' => $p, 'kendaraan' => $k] = $this->buatSkenaroPeminjamanAktif();

        $riwayat = RiwayatPengembalian::factory()->create([
            'peminjaman_id' => $p->id,
            'status'        => 'pending',
        ]);

        $this->service->tolakPengembalian($riwayat);

        // Hanya riwayat yang berubah
        $this->assertSame('ditolak',  $riwayat->fresh()->status);

        // Peminjaman dan kendaraan TIDAK berubah
        $this->assertSame('dipinjam', $p->fresh()->status_peminjaman);
        $this->assertSame('dipinjam', $k->fresh()->status);
    }

    #[Test]
    public function tolakPengembalian_tidak_mengisi_tanggal_konfirmasi(): void
    {
        ['peminjaman' => $p] = $this->buatSkenaroPeminjamanAktif();

        $riwayat = RiwayatPengembalian::factory()->create([
            'peminjaman_id' => $p->id,
            'status'        => 'pending',
        ]);

        $this->service->tolakPengembalian($riwayat);

        $this->assertNull($riwayat->fresh()->tanggal_konfirmasi);
    }

    // ── 25.2.5: ajukan ulang setelah ditolak — diperbolehkan ─────────────

    #[Test]
    public function ajukanPengembalian_diperbolehkan_setelah_sebelumnya_ditolak(): void
    {
        ['peminjaman' => $p] = $this->buatSkenaroPeminjamanAktif();

        $riwayatPertama = $this->service->ajukanPengembalian($p, 'Pertama');
        $this->service->tolakPengembalian($riwayatPertama);

        // Setelah ditolak, boleh ajukan lagi
        $riwayatKedua = $this->service->ajukanPengembalian($p, 'Kedua');

        $this->assertSame('pending', $riwayatKedua->status);
        $this->assertDatabaseCount('riwayat_pengembalians', 2);
    }
}
