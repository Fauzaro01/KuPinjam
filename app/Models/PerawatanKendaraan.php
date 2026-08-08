<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerawatanKendaraan extends Model
{
    protected $fillable = [
        'kendaraan_id',
        'admin_id',
        'jenis_perawatan',
        'tanggal_mulai',
        'estimasi_selesai',
        'tanggal_selesai',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai'     => 'date',
        'estimasi_selesai'  => 'date',
        'tanggal_selesai'   => 'date',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
