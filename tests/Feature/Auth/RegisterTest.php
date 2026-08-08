<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function dataValid(): array
    {
        return [
            'username'              => 'Pengguna Baru',
            'email'                 => 'baru@kupinjam.test',
            'no_telp'               => '081200009999',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ];
    }

    // ── 26.2.1: Register berhasil ───────────────────────────────────────────

    #[Test]
    public function register_berhasil_menyimpan_user_dan_redirect_ke_dashboard(): void
    {
        $response = $this->post(route('store'), $this->dataValid());

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'baru@kupinjam.test',
            'role'  => 'karyawan',
        ]);
    }

    #[Test]
    public function register_berhasil_langsung_login(): void
    {
        $this->post(route('store'), $this->dataValid());

        $this->assertAuthenticated();
    }

    // ── 26.2.2: Register gagal — duplikat email ─────────────────────────────

    #[Test]
    public function register_gagal_jika_email_sudah_dipakai(): void
    {
        User::factory()->create(['email' => 'baru@kupinjam.test']);

        $response = $this->post(route('store'), $this->dataValid());

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ── 26.2.3: Register gagal — duplikat no_telp ───────────────────────────

    #[Test]
    public function register_gagal_jika_no_telp_sudah_dipakai(): void
    {
        User::factory()->create(['no_telp' => '081200009999']);

        $response = $this->post(route('store'), $this->dataValid());

        $response->assertSessionHasErrors('no_telp');
    }

    // ── 26.2.4: Register gagal — password tidak cocok ───────────────────────

    #[Test]
    public function register_gagal_jika_password_tidak_cocok(): void
    {
        $data = $this->dataValid();
        $data['password_confirmation'] = 'berbeda123';

        $response = $this->post(route('store'), $data);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'baru@kupinjam.test']);
    }

    // ── 26.2.5: Halaman register hanya untuk guest ──────────────────────────

    #[Test]
    public function halaman_register_dapat_diakses_oleh_guest(): void
    {
        $this->get(route('register'))->assertOk();
    }

    #[Test]
    public function halaman_register_redirect_jika_sudah_login(): void
    {
        $user = User::factory()->create(['role' => 'karyawan']);

        $this->actingAs($user)
             ->get(route('register'))
             ->assertRedirect();
    }
}
