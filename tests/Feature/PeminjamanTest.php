<?php

namespace Tests\Feature;

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PeminjamanTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'administrator']);
    }

    private function karyawan(): User
    {
        return User::factory()->create(['role' => 'karyawan']);
    }

    private function kendaraanTersedia(): Kendaraan
    {
        return Kendaraan::factory()->create(['status' => 'tersedia']);
    }

    // ── 28.1.1: Karyawan berhasil pinjam via route pinjam ───────────────────

    #[Test]
    public function karyawan_berhasil_pinjam_kendaraan_tersedia(): void
    {
        $user      = $this->karyawan();
        $kendaraan = $this->kendaraanTersedia();

        $response = $this->actingAs($user)->post(route('peminjaman.pinjam'), [
            'kendaraan_id'   => $kendaraan->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali'=> now()->addDays(3)->toDateString(),
            'tujuan'         => 'Perjalanan dinas',
        ]);

        $response->assertRedirect(route('peminjaman.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('peminjamans', [
            'user_id'           => $user->id,
            'kendaraan_id'      => $kendaraan->id,
            'status_peminjaman' => 'dipinjam',
        ]);

        $this->assertSame('dipinjam', $kendaraan->fresh()->status);
    }

    // ── 28.1.2: Kendaraan tidak tersedia → error flash ──────────────────────

    #[Test]
    public function karyawan_tidak_bisa_pinjam_kendaraan_sudah_dipinjam(): void
    {
        $user      = $this->karyawan();
        $kendaraan = Kendaraan::factory()->create(['status' => 'dipinjam']);

        $response = $this->actingAs($user)->post(route('peminjaman.pinjam'), [
            'kendaraan_id'   => $kendaraan->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali'=> now()->addDays(3)->toDateString(),
            'tujuan'         => 'Test',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('peminjamans', 0);
    }

    // ── 28.1.3: Admin membuat peminjaman untuk karyawan ─────────────────────

    #[Test]
    public function admin_bisa_buat_peminjaman_untuk_karyawan_lain(): void
    {
        $admin     = $this->admin();
        $karyawan  = $this->karyawan();
        $kendaraan = $this->kendaraanTersedia();

        $response = $this->actingAs($admin)->post(route('peminjaman.store'), [
            'user_id'        => $karyawan->id,
            'kendaraan_id'   => $kendaraan->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali'=> now()->addDays(5)->toDateString(),
            'tujuan'         => 'Kunjungan klien',
        ]);

        $response->assertRedirect(route('peminjaman.index'));
        $this->assertDatabaseHas('peminjamans', [
            'user_id'           => $karyawan->id,
            'status_peminjaman' => 'dipinjam',
        ]);
    }

    // ── 28.1.4: Karyawan tidak bisa akses route store admin ─────────────────

    #[Test]
    public function karyawan_tidak_bisa_post_ke_peminjaman_store(): void
    {
        $user      = $this->karyawan();
        $karyawan2 = $this->karyawan();
        $kendaraan = $this->kendaraanTersedia();

        $this->actingAs($user)->post(route('peminjaman.store'), [
            'user_id'        => $karyawan2->id,
            'kendaraan_id'   => $kendaraan->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali'=> now()->addDays(3)->toDateString(),
            'tujuan'         => 'Test',
        ])->assertForbidden();
    }

    // ── 28.1.5: Admin bisa lihat semua peminjaman ───────────────────────────

    #[Test]
    public function admin_melihat_semua_peminjaman(): void
    {
        $k1 = $this->karyawan();
        $k2 = $this->karyawan();
        Peminjaman::factory()->create(['user_id' => $k1->id]);
        Peminjaman::factory()->create(['user_id' => $k2->id]);

        $this->actingAs($this->admin())
             ->get(route('peminjaman.index'))
             ->assertOk()
             ->assertSee($k1->username)
             ->assertSee($k2->username);
    }

    // ── 28.1.6: Karyawan hanya lihat peminjaman miliknya ────────────────────

    #[Test]
    public function karyawan_hanya_melihat_peminjaman_miliknya(): void
    {
        $user  = $this->karyawan();
        $other = $this->karyawan();

        Peminjaman::factory()->create(['user_id' => $user->id,  'tujuan' => 'Milik saya']);
        Peminjaman::factory()->create(['user_id' => $other->id, 'tujuan' => 'Milik orang lain']);

        $this->actingAs($user)
             ->get(route('peminjaman.index'))
             ->assertOk()
             ->assertSee('Milik saya')
             ->assertDontSee('Milik orang lain');
    }
}
