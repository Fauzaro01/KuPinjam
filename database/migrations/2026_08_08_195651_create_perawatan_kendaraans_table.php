<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perawatan_kendaraans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->cascadeOnDelete();
            $table->string('admin_id', 13)->nullable();
            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
            $table->string('jenis_perawatan');        // Servis Rutin, Perbaikan, Ganti Oli, dll
            $table->date('tanggal_mulai');
            $table->date('estimasi_selesai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['dijadwalkan', 'berlangsung', 'selesai'])->default('dijadwalkan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perawatan_kendaraans');
    }
};
