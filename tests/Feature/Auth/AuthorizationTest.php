<?php

namespace Tests\Feature\Auth;

use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function karyawan(): User
    {
        return User::factory()->create(['role' => 'karyawan']);
    }

    // ── 27.1.1: Guest diblokir dari route auth ──────────────────────────────

    #[Test]
    public function guest_redirect_ke_login_saat_akses_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    #[Test]
    public function guest_redirect_ke_login_saat_akses_kendaraan(): void
    {
        $this->get(route('kendaraan.index'))->assertRedirect(route('login'));
    }

    #[Test]
    public function guest_redirect_ke_login_saat_akses_peminjaman(): void
    {
        $this->get(route('peminjaman.index'))->assertRedirect(route('login'));
    }

    // ── 27.1.2: Karyawan tidak bisa akses route admin ───────────────────────

    #[Test]
    public function karyawan_tidak_bisa_akses_halaman_usermanagement(): void
    {
        $this->actingAs($this->karyawan())
             ->get(route('usermanagement.index'))
             ->assertForbidden();
    }

    #[Test]
    public function karyawan_tidak_bisa_akses_halaman_pengembalian_admin(): void
    {
        $this->actingAs($this->karyawan())
             ->get(route('pengembalian.index'))
             ->assertForbidden();
    }

    #[Test]
    public function karyawan_tidak_bisa_hapus_kendaraan(): void
    {
        $kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);

        $this->actingAs($this->karyawan())
             ->delete(route('kendaraan.destroy', $kendaraan))
             ->assertForbidden();

        // Kendaraan tetap ada di DB
        $this->assertDatabaseHas('kendaraans', ['id' => $kendaraan->id]);
    }

    #[Test]
    public function karyawan_tidak_bisa_buat_kendaraan_baru(): void
    {
        $this->actingAs($this->karyawan())
             ->post(route('kendaraan.store'), [
                 'plat_nomor'      => 'B 9999 XX',
                 'merk'            => 'Toyota',
                 'model'           => 'Avanza',
                 'tahun'           => 2022,
                 'jenis_kendaraan' => 'mobil',
             ])
             ->assertForbidden();
    }

    #[Test]
    public function karyawan_tidak_bisa_akses_form_buat_peminjaman_admin(): void
    {
        $this->actingAs($this->karyawan())
             ->get(route('peminjaman.create'))
             ->assertForbidden();
    }

    // ── 27.1.3: Administrator bisa akses semua ──────────────────────────────

    #[Test]
    public function administrator_bisa_akses_usermanagement(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);

        $this->actingAs($admin)
             ->get(route('usermanagement.index'))
             ->assertOk();
    }

    #[Test]
    public function administrator_bisa_akses_halaman_pengembalian(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);

        $this->actingAs($admin)
             ->get(route('pengembalian.index'))
             ->assertOk();
    }
}
