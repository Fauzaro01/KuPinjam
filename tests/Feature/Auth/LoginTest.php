<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // ── 26.1.1: Login berhasil ──────────────────────────────────────────────

    #[Test]
    public function login_berhasil_dengan_kredensial_valid(): void
    {
        $user = User::factory()->create([
            'role'     => 'karyawan',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('authenticate'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function login_berhasil_untuk_administrator(): void
    {
        $admin = User::factory()->create([
            'role'     => 'administrator',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('authenticate'), [
            'email'    => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    // ── 26.1.2: Login gagal ─────────────────────────────────────────────────

    #[Test]
    public function login_gagal_dengan_password_salah(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->post(route('authenticate'), [
            'email'    => $user->email,
            'password' => 'salah-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    #[Test]
    public function login_gagal_dengan_email_tidak_terdaftar(): void
    {
        $response = $this->post(route('authenticate'), [
            'email'    => 'tidakada@test.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ── 26.1.3: Halaman login dapat diakses ─────────────────────────────────

    #[Test]
    public function halaman_login_dapat_diakses_oleh_guest(): void
    {
        $this->get(route('login'))->assertOk();
    }

    #[Test]
    public function halaman_login_redirect_jika_sudah_login(): void
    {
        $user = User::factory()->create(['role' => 'karyawan']);

        $this->actingAs($user)
             ->get(route('login'))
             ->assertRedirect();
    }

    // ── 26.2.1: Logout ──────────────────────────────────────────────────────

    #[Test]
    public function logout_menghapus_session_dan_redirect_ke_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
