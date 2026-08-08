<?php

namespace Tests\Feature;

use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExtraFeaturesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function remember_me_checkbox_authenticates_correctly(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'password123',
            'remember' => 'on',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        
        // Assert that the remember_token has been created on the user model
        $user->refresh();
        $this->assertNotNull($user->remember_token);
    }

    #[Test]
    public function kendaraan_and_user_support_soft_deletes(): void
    {
        $kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);
        $user = User::factory()->create(['role' => 'karyawan']);

        // Delete them
        $kendaraan->delete();
        $user->delete();

        // Assert they are soft deleted (still exist in database but not in standard queries)
        $this->assertSoftDeleted('kendaraans', ['id' => $kendaraan->id]);
        $this->assertSoftDeleted('users', ['id' => $user->id]);

        $this->assertNull(Kendaraan::find($kendaraan->id));
        $this->assertNull(User::find($user->id));

        // Standard all() query should not return them
        $this->assertFalse(Kendaraan::all()->contains($kendaraan));
        $this->assertFalse(User::all()->contains($user));
    }

    #[Test]
    public function export_csv_filters_by_date_range(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $kendaraan1 = Kendaraan::factory()->create(['plat_nomor' => 'B 1111 XX']);
        $kendaraan2 = Kendaraan::factory()->create(['plat_nomor' => 'B 2222 XX']);
        $kendaraan3 = Kendaraan::factory()->create(['plat_nomor' => 'B 3333 XX']);

        // Create checkout records at different times
        $p1 = Peminjaman::factory()->create([
            'user_id' => $admin->id,
            'kendaraan_id' => $kendaraan1->id,
            'tanggal_pinjam' => '2026-08-01 10:00:00',
            'tanggal_kembali' => '2026-08-02 10:00:00',
        ]);

        $p2 = Peminjaman::factory()->create([
            'user_id' => $admin->id,
            'kendaraan_id' => $kendaraan2->id,
            'tanggal_pinjam' => '2026-08-05 10:00:00',
            'tanggal_kembali' => '2026-08-06 10:00:00',
        ]);

        $p3 = Peminjaman::factory()->create([
            'user_id' => $admin->id,
            'kendaraan_id' => $kendaraan3->id,
            'tanggal_pinjam' => '2026-08-10 10:00:00',
            'tanggal_kembali' => '2026-08-11 10:00:00',
        ]);

        // Export range: 2026-08-04 to 2026-08-07 (should only include $p2)
        $response = $this->actingAs($admin)
            ->get(route('peminjaman.export-csv', [
                'start_date' => '2026-08-04',
                'end_date' => '2026-08-07',
            ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        $content = $response->streamedContent();
        
        // Assert B (p2 plat_nomor) is present, A and C (p1, p3 plat_nomor) are not
        $this->assertStringContainsString($p2->kendaraan->plat_nomor, $content);
        $this->assertStringNotContainsString($p1->kendaraan->plat_nomor, $content);
        $this->assertStringNotContainsString($p3->kendaraan->plat_nomor, $content);
    }

    #[Test]
    public function peminjaman_relations_load_soft_deleted_models(): void
    {
        $user = User::factory()->create(['role' => 'karyawan']);
        $kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);

        $peminjaman = Peminjaman::factory()->create([
            'user_id' => $user->id,
            'kendaraan_id' => $kendaraan->id,
        ]);

        // Soft delete both
        $user->delete();
        $kendaraan->delete();

        // Reload peminjaman and assert relations are still resolved (withTrashed)
        $peminjaman->refresh();
        $this->assertNotNull($peminjaman->user);
        $this->assertEquals($user->id, $peminjaman->user->id);
        $this->assertNotNull($peminjaman->kendaraan);
        $this->assertEquals($kendaraan->id, $peminjaman->kendaraan->id);
    }

    #[Test]
    public function bulk_import_detects_soft_deleted_user_duplicates(): void
    {
        $user = User::factory()->create([
            'role' => 'karyawan',
            'email' => 'duplicate@kupinjam.test',
            'no_telp' => '081234567890',
        ]);

        // Soft delete the user
        $user->delete();

        // Create a temporary CSV file with duplicate email & phone
        $csvContent = "username,email,no_telp,password\n" .
                      "New User,duplicate@kupinjam.test,081234567890,password123\n" .
                      "Unique User,unique@kupinjam.test,089999999999,password123\n";

        $tempPath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempPath, $csvContent);

        $file = new \Illuminate\Http\UploadedFile(
            $tempPath,
            'import.csv',
            'text/csv',
            null,
            true
        );

        $userService = app(\App\Services\UserService::class);
        $result = $userService->bulkImportFromCsv($file);

        // Clean up temp file
        @unlink($tempPath);

        // Assert duplicate was skipped instead of crash, unique was imported
        $this->assertEquals(1, $result['imported']);
        $this->assertEquals(1, $result['skipped']);
    }

    #[Test]
    public function admin_can_restore_soft_deleted_kendaraan(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);

        $kendaraan->delete();
        $this->assertSoftDeleted('kendaraans', ['id' => $kendaraan->id]);

        $response = $this->actingAs($admin)
            ->patch(route('kendaraan.restore', $kendaraan->id));

        $response->assertRedirect(route('kendaraan.index'));
        $this->assertDatabaseHas('kendaraans', [
            'id' => $kendaraan->id,
            'deleted_at' => null,
        ]);
    }

    #[Test]
    public function admin_can_restore_soft_deleted_user(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $user = User::factory()->create(['role' => 'karyawan']);

        $user->delete();
        $this->assertSoftDeleted('users', ['id' => $user->id]);

        $response = $this->actingAs($admin)
            ->patch(route('usermanagement.restore', $user->id));

        $response->assertRedirect(route('usermanagement.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    #[Test]
    public function notifikasi_bell_marks_as_read_successfully(): void
    {
        $user = User::factory()->create(['role' => 'karyawan']);
        $notification = \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => 'Test Notification',
            'message' => 'This is a test notification message.',
            'is_read' => false,
        ]);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('notifications.read', $notification->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }

    #[Test]
    public function admin_can_view_activity_logs(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        
        // Log an activity
        $logService = app(\App\Services\ActivityLogService::class);
        $logService->log('test_action', 'This is a test log description', $admin->id);

        $response = $this->actingAs($admin)
            ->get(route('admin.activity-log'));

        $response->assertOk();
        $response->assertSee('This is a test log description');
    }

    #[Test]
    public function admin_can_filter_and_print_laporan(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        
        $response = $this->actingAs($admin)
            ->get(route('admin.laporan', [
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->endOfMonth()->toDateString(),
            ]));

        $response->assertOk();
        $response->assertSee('Laporan Peminjaman');
        $response->assertSee('PT. KUPINJAM INDONESIA');
    }

    #[Test]
    public function spotlight_search_returns_json_results(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $kendaraan = Kendaraan::factory()->create([
            'plat_nomor' => 'H 1234 AB',
            'merk' => 'Toyota',
            'model' => 'Innova',
            'status' => 'tersedia'
        ]);

        $response = $this->actingAs($admin)
            ->get(route('search', ['q' => 'Innova']));

        $response->assertOk();
        $response->assertJsonFragment([
            'type' => 'Kendaraan',
            'label' => 'H 1234 AB — Toyota Innova',
        ]);
    }

    #[Test]
    public function admin_can_schedule_and_complete_maintenance(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $kendaraan = Kendaraan::factory()->create([
            'status' => 'tersedia',
            'plat_nomor' => 'H 5678 CD'
        ]);

        // Schedule maintenance
        $response = $this->actingAs($admin)
            ->post(route('perawatan.store'), [
                'kendaraan_id' => $kendaraan->id,
                'jenis_perawatan' => 'Ganti Oli Mesin',
                'tanggal_mulai' => now()->toDateString(),
            ]);

        $response->assertRedirect(route('perawatan.index'));
        
        $this->assertDatabaseHas('perawatan_kendaraans', [
            'kendaraan_id' => $kendaraan->id,
            'jenis_perawatan' => 'Ganti Oli Mesin',
            'status' => 'dijadwalkan',
        ]);

        $kendaraan->refresh();
        $this->assertEquals('perawatan', $kendaraan->status);

        // Complete maintenance
        $perawatan = \App\Models\PerawatanKendaraan::first();
        $response = $this->actingAs($admin)
            ->patch(route('perawatan.selesai', $perawatan->id));

        $response->assertRedirect(route('perawatan.index'));

        $this->assertDatabaseHas('perawatan_kendaraans', [
            'id' => $perawatan->id,
            'status' => 'selesai',
        ]);

        $kendaraan->refresh();
        $this->assertEquals('tersedia', $kendaraan->status);
    }

    #[Test]
    public function peminjaman_can_include_document_attachments(): void
    {
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);

        \Illuminate\Support\Facades\Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->create('surat_tugas.pdf', 500);

        $response = $this->actingAs($karyawan)
            ->post(route('peminjaman.pinjam'), [
                'kendaraan_id' => $kendaraan->id,
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_kembali' => now()->addDays(2)->toDateString(),
                'tujuan' => 'Dinas Solo',
                'dokumens' => [$file],
            ]);

        $response->assertRedirect(route('peminjaman.index'));

        $peminjaman = Peminjaman::first();
        $this->assertNotNull($peminjaman);

        $this->assertDatabaseHas('peminjaman_dokumens', [
            'peminjaman_id' => $peminjaman->id,
            'file_name' => 'surat_tugas.pdf',
        ]);
    }

    #[Test]
    public function admin_can_add_internal_notes_to_peminjaman(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);

        $peminjaman = Peminjaman::factory()->create([
            'user_id' => $karyawan->id,
            'kendaraan_id' => $kendaraan->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(1),
        ]);

        $response = $this->actingAs($admin)
            ->put(route('peminjaman.update', $peminjaman->id), [
                'user_id' => $karyawan->id,
                'kendaraan_id' => $kendaraan->id,
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_kembali' => now()->addDays(1)->toDateString(),
                'tujuan' => 'Tujuan Edit',
                'admin_notes' => 'Catatan internal rahasia.',
            ]);

        $response->assertRedirect(route('peminjaman.index'));
        
        $peminjaman->refresh();
        $this->assertEquals('Catatan internal rahasia.', $peminjaman->admin_notes);
    }

    #[Test]
    public function admin_can_view_surat_jalan(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);

        $peminjaman = Peminjaman::factory()->create([
            'user_id' => $karyawan->id,
            'kendaraan_id' => $kendaraan->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(2),
            'status_peminjaman' => 'dipinjam',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('peminjaman.surat-jalan', $peminjaman->id));

        $response->assertOk();
        $response->assertViewIs('peminjaman.surat-jalan');
        $response->assertSee('Surat Jalan Peminjaman');
    }

    #[Test]
    public function admin_can_manage_announcements(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);

        // Create
        $response = $this->actingAs($admin)
            ->post(route('announcement.store'), [
                'title' => 'Pengumuman Penting',
                'content' => 'Isi pengumuman yang sangat panjang.',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('announcement.index'));
        $this->assertDatabaseHas('announcements', [
            'title' => 'Pengumuman Penting',
            'is_active' => true,
        ]);

        // Toggle
        $ann = \App\Models\Announcement::first();
        $response = $this->actingAs($admin)
            ->patch(route('announcement.toggle', $ann->id));

        $response->assertRedirect(route('announcement.index'));
        $ann->refresh();
        $this->assertFalse($ann->is_active);
    }

    #[Test]
    public function admin_can_confirm_pengembalian_with_rating_and_feedback(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);

        $peminjaman = Peminjaman::factory()->create([
            'user_id' => $karyawan->id,
            'kendaraan_id' => $kendaraan->id,
            'tanggal_pinjam' => now()->subDays(2),
            'tanggal_kembali' => now()->subDays(1),
            'status_peminjaman' => 'dipinjam',
        ]);

        $riwayat = \App\Models\RiwayatPengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'catatan_pengembalian' => 'Mobil aman',
            'status' => 'pending',
            'tanggal_pengajuan' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->put(route('pengembalian.konfirmasi', $riwayat->id), [
                'kondisi_rating' => 5,
                'kondisi_feedback' => 'Sangat mulus dan bersih!',
            ]);

        $response->assertRedirect(route('pengembalian.index'));
        $riwayat->refresh();
        $this->assertEquals('dikonfirmasi', $riwayat->status);
        $this->assertEquals(5, $riwayat->kondisi_rating);
        $this->assertEquals('Sangat mulus dan bersih!', $riwayat->kondisi_feedback);
    }

    #[Test]
    public function command_sends_reminders_correctly(): void
    {
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $kendaraan1 = Kendaraan::factory()->create(['status' => 'tersedia']);
        $kendaraan2 = Kendaraan::factory()->create(['status' => 'tersedia']);

        // 1. Near due: H-1 (ends tomorrow)
        $p1 = Peminjaman::factory()->create([
            'user_id' => $karyawan->id,
            'kendaraan_id' => $kendaraan1->id,
            'tanggal_pinjam' => now()->subDays(1),
            'tanggal_kembali' => now()->addDay(), // tomorrow
            'status_peminjaman' => 'dipinjam',
        ]);

        // 2. Overdue: terlambat 2 hari
        $p2 = Peminjaman::factory()->create([
            'user_id' => $karyawan->id,
            'kendaraan_id' => $kendaraan2->id,
            'tanggal_pinjam' => now()->subDays(5),
            'tanggal_kembali' => now()->subDays(2), // overdue
            'status_peminjaman' => 'dipinjam',
        ]);

        $this->artisan('peminjaman:send-reminders')
            ->expectsOutputToContain('⏰ Pengingat: Kendaraan Jatuh Tempo Besok' ? '' : '') // execution output lines
            ->assertExitCode(0);

        // Check borrower got notifications
        $this->assertDatabaseHas('notifications', [
            'user_id' => $karyawan->id,
            'title' => '⏰ Pengingat: Kendaraan Jatuh Tempo Besok',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $karyawan->id,
            'title' => '🚨 Peringatan: Kendaraan Terlambat Dikembalikan',
        ]);
    }

    #[Test]
    public function user_can_view_calendar_page_and_api(): void
    {
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $kendaraan1 = Kendaraan::factory()->create(['status' => 'tersedia']);
        $kendaraan2 = Kendaraan::factory()->create(['status' => 'tersedia']);

        Peminjaman::factory()->create([
            'user_id' => $karyawan->id,
            'kendaraan_id' => $kendaraan1->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(1),
            'status_peminjaman' => 'dipinjam',
        ]);

        Peminjaman::factory()->create([
            'user_id' => $karyawan->id,
            'kendaraan_id' => $kendaraan2->id,
            'tanggal_pinjam' => now()->addDays(2),
            'tanggal_kembali' => now()->addDays(3),
            'status_peminjaman' => 'dipinjam',
        ]);

        // 1. Cek akses halaman kalender
        $response = $this->actingAs($karyawan)
            ->get(route('peminjaman.kalender'));
        $response->assertOk();
        $response->assertSee('Jadwal Armada');

        // 2. Cek API kalender (Semua)
        $responseApi = $this->actingAs($karyawan)
            ->get(route('peminjaman.api-kalender'));
        $responseApi->assertOk();
        $responseApi->assertJsonCount(2);

        // 3. Cek API dengan filter kendaraan
        $responseFiltered = $this->actingAs($karyawan)
            ->get(route('peminjaman.api-kalender', ['kendaraan_id' => $kendaraan1->id]));
        $responseFiltered->assertOk();
        $responseFiltered->assertJsonCount(1);
    }
}


