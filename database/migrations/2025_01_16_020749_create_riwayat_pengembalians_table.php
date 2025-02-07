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
            $table->id('riwayat_id');
            $table->foreignId('peminjaman_id')->constrained('peminjamans');
            $table->date('tanggal_kembali');
            $table->enum('status_kondisi', ['baik', 'rusak', 'hilang']);
            $table->text('keterangan')->nullable();
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
