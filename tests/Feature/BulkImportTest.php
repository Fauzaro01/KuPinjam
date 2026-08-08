<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BulkImportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'administrator']);
    }

    private function buatCsvFile(string $content, string $nama = 'users.csv'): UploadedFile
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tmpPath, $content);
        return new UploadedFile($tmpPath, $nama, 'text/csv', null, true);
    }

    // ── 30.1.1: Import berhasil 3 baris baru ─────────────────────────────────

    #[Test]
    public function import_berhasil_menyimpan_user_baru(): void
    {
        $csv  = "username,email,no_telp,password\n";
        $csv .= "User A,usera@test.com,081111111111,password123\n";
        $csv .= "User B,userb@test.com,082222222222,password123\n";
        $csv .= "User C,userc@test.com,083333333333,password123\n";

        $response = $this->actingAs($this->admin())
            ->post(route('usermanagement.bulkstoreuser'), [
                'file' => $this->buatCsvFile($csv),
            ]);

        $response->assertRedirect(route('usermanagement.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('users', 4); // 1 admin + 3 import
    }

    // ── 30.1.2: Duplikat dilewati, ringkasan akurat ──────────────────────────

    #[Test]
    public function import_melewati_duplikat_email_dan_melaporkan_ringkasan(): void
    {
        User::factory()->create(['email' => 'ada@test.com', 'no_telp' => '089999999999']);

        $csv  = "username,email,no_telp,password\n";
        $csv .= "Duplikat,ada@test.com,088888888888,password123\n"; // duplikat email
        $csv .= "Baru,baru@test.com,087777777777,password123\n";    // unik

        $response = $this->actingAs($this->admin())
            ->post(route('usermanagement.bulkstoreuser'), [
                'file' => $this->buatCsvFile($csv),
            ]);

        $response->assertSessionHas('success');
        // Flash message harus menyebut angka imported dan skipped
        $this->assertStringContainsString('1', session('success'));
        $this->assertDatabaseCount('users', 3); // 1 admin + 1 existing + 1 baru
    }

    #[Test]
    public function import_imported_plus_skipped_sama_dengan_total_baris(): void
    {
        // 2 baris: 1 unik, 1 duplikat no_telp
        User::factory()->create(['no_telp' => '086666666666']);

        $csv  = "username,email,no_telp,password\n";
        $csv .= "Baru,baru2@test.com,085555555555,password123\n";     // unik
        $csv .= "Dup,dup@test.com,086666666666,password123\n";         // duplikat no_telp

        $this->actingAs($this->admin())
             ->post(route('usermanagement.bulkstoreuser'), [
                 'file' => $this->buatCsvFile($csv),
             ]);

        // 2 total = 1 imported + 1 skipped → 2 non-admin users di DB
        $this->assertDatabaseCount('users', 3); // 1 admin + 1 existing + 1 baru
    }

    // ── 30.1.3: File invalid (bukan CSV) ─────────────────────────────────────

    #[Test]
    public function import_ditolak_jika_file_bukan_csv(): void
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'txt');
        file_put_contents($tmpPath, 'ini bukan csv');
        $txtFile = new UploadedFile($tmpPath, 'users.txt', 'text/plain', null, true);

        $response = $this->actingAs($this->admin())
            ->post(route('usermanagement.bulkstoreuser'), [
                'file' => $txtFile,
            ]);

        $response->assertSessionHasErrors('file');
        // Tidak ada user baru selain admin
        $this->assertDatabaseCount('users', 1);
    }

    // ── 30.1.4: Non-admin tidak bisa bulk import ─────────────────────────────

    #[Test]
    public function karyawan_tidak_bisa_bulk_import(): void
    {
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $csv      = "username,email,no_telp,password\nbaru,x@x.com,011111111111,pw123456\n";

        $this->actingAs($karyawan)
             ->post(route('usermanagement.bulkstoreuser'), [
                 'file' => $this->buatCsvFile($csv),
             ])
             ->assertForbidden();
    }
}
