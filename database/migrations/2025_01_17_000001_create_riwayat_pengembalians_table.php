<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')
                  ->constrained('peminjamans')
                  ->cascadeOnDelete();
            $table->text('catatan_pengembalian')->nullable();
            $table->enum('status', ['pending', 'dikonfirmasi', 'ditolak'])->default('pending');
            $table->dateTime('tanggal_pengajuan');
            $table->dateTime('tanggal_konfirmasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pengembalians');
    }
};
