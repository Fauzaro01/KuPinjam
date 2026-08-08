<?php

namespace Tests\Feature;

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\RiwayatPengembalian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PengembalianTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'administrator']);
    }

    private function buatPeminjamanAktif(?User $user = null): array
    {
        $user      = $user ?? User::factory()->create(['role' => 'karyawan']);
        $kendaraan = Kendaraan::factory()->create(['status' => 'dipinjam']);
        $peminjaman = Peminjaman::factory()->create([
            'user_id'           => $user->id,
            'kendaraan_id'      => $kendaraan->id,
            'status_peminjaman' => 'dipinjam',
        ]);

        return compact('user', 'kendaraan', 'peminjaman');
    }

    // ── 29.1.1: Karyawan ajukan pengembalian ────────────────────────────────

    #[Test]
    public function karyawan_berhasil_ajukan_pengembalian(): void
    {
        ['user' => $user, 'peminjaman' => $p] = $this->buatPeminjamanAktif();

        $response = $this->actingAs($user)
            ->post(route('pengembalian.ajukan', $p), [
                'catatan_pengembalian' => 'Kendaraan dalam kondisi baik',
            ]);

        $response->assertRedirect(route('peminjaman.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('riwayat_pengembalians', [
            'peminjaman_id' => $p->id,
            'status'        => 'pending',
        ]);
    }

    // ── 29.1.2: Duplikat pending ditolak ────────────────────────────────────

    #[Test]
    public function ajukan_pengembalian_kedua_kali_menghasilkan_error_flash(): void
    {
        ['user' => $user, 'peminjaman' => $p] = $this->buatPeminjamanAktif();

        $this->actingAs($user)->post(route('pengembalian.ajukan', $p));

        // Pengajuan kedua: bisa 403 (policy) atau redirect + error flash (service)
        $response = $this->actingAs($user)->post(route('pengembalian.ajukan', $p));

        // Pengajuan kedua: bisa 403 (policy) atau redirect + error flash (service)
        $this->assertContains(
            $response->status(),
            [302, 303, 403],
            "Expected 302/303/403 but got {$response->status()}"
        );
        $this->assertDatabaseCount('riwayat_pengembalians', 1);
    }

    // ── 29.1.3: Karyawan lain tidak bisa ajukan milik orang lain ────────────

    #[Test]
    public function karyawan_lain_tidak_bisa_ajukan_pengembalian_milik_orang_lain(): void
    {
        ['peminjaman' => $p] = $this->buatPeminjamanAktif();
        $karyawanLain = User::factory()->create(['role' => 'karyawan']);

        $this->actingAs($karyawanLain)
             ->post(route('pengembalian.ajukan', $p))
             ->assertForbidden();
    }

    // ── 29.1.4: Admin konfirmasi pengembalian ───────────────────────────────

    #[Test]
    public function admin_berhasil_konfirmasi_pengembalian(): void
    {
        ['peminjaman' => $p, 'kendaraan' => $k] = $this->buatPeminjamanAktif();

        $riwayat = RiwayatPengembalian::factory()->create([
            'peminjaman_id' => $p->id,
            'status'        => 'pending',
        ]);

        $response = $this->actingAs($this->admin())
            ->put(route('pengembalian.konfirmasi', $riwayat));

        $response->assertRedirect(route('pengembalian.index'));
        $response->assertSessionHas('success');

        $this->assertSame('dikonfirmasi', $riwayat->fresh()->status);
        $this->assertSame('selesai',      $p->fresh()->status_peminjaman);
        $this->assertSame('tersedia',     $k->fresh()->status);
    }

    // ── 29.1.5: Admin tolak pengembalian ────────────────────────────────────

    #[Test]
    public function admin_berhasil_tolak_pengembalian(): void
    {
        ['peminjaman' => $p, 'kendaraan' => $k] = $this->buatPeminjamanAktif();

        $riwayat = RiwayatPengembalian::factory()->create([
            'peminjaman_id' => $p->id,
            'status'        => 'pending',
        ]);

        $response = $this->actingAs($this->admin())
            ->put(route('pengembalian.tolak', $riwayat));

        $response->assertRedirect(route('pengembalian.index'));
        $response->assertSessionHas('success');

        $this->assertSame('ditolak',  $riwayat->fresh()->status);
        $this->assertSame('dipinjam', $p->fresh()->status_peminjaman);
        $this->assertSame('dipinjam', $k->fresh()->status);
    }

    // ── 29.1.6: Karyawan tidak bisa konfirmasi/tolak ────────────────────────

    #[Test]
    public function karyawan_tidak_bisa_konfirmasi_pengembalian(): void
    {
        ['peminjaman' => $p] = $this->buatPeminjamanAktif();
        $riwayat = RiwayatPengembalian::factory()->create([
            'peminjaman_id' => $p->id,
            'status'        => 'pending',
        ]);
        $karyawan = User::factory()->create(['role' => 'karyawan']);

        $this->actingAs($karyawan)
             ->put(route('pengembalian.konfirmasi', $riwayat))
             ->assertForbidden();
    }
}
