<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_pengembalians', function (Blueprint $table) {
            // Rating kondisi kendaraan (1–5 bintang), nullable karena diisi setelah dikonfirmasi
            $table->tinyInteger('kondisi_rating')->unsigned()->nullable()->after('status');
            // Komentar/feedback singkat dari admin tentang kondisi kendaraan
            $table->string('kondisi_feedback', 500)->nullable()->after('kondisi_rating');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_pengembalians', function (Blueprint $table) {
            $table->dropColumn(['kondisi_rating', 'kondisi_feedback']);
        });
    }
};
