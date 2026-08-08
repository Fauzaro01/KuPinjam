<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->cascadeOnDelete();
            $table->string('uploader_id', 13);
            $table->foreign('uploader_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('jenis'); // surat_tugas, foto_kondisi, dll
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_dokumens');
    }
};
