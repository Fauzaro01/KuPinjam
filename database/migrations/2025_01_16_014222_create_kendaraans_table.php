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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id('id');
            $table->string('plat_nomor', 20);
            $table->string('merk', 50);
            $table->string('model', 50);
            $table->integer('tahun');
            $table->enum('jenis_kendaraan', ['motor', 'mobil']);
            $table->enum('status', ['tersedia', 'dipinjam', 'perawatan'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
