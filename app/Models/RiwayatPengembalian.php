<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPengembalian extends Model
{
    use HasFactory;
    protected $table = 'riwayat_pengembalians';

    protected $fillable = [
        'peminjaman_id',
        'catatan_pengembalian',
        'status',
        'tanggal_pengajuan',
        'tanggal_konfirmasi',
    ];

    protected $casts = [
        'tanggal_pengajuan'  => 'datetime',
        'tanggal_konfirmasi' => 'datetime',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
