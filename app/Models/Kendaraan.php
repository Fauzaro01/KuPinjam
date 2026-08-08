<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kendaraan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kendaraans';

    protected $fillable = [
        'plat_nomor',
        'merk',
        'model',
        'tahun',
        'jenis_kendaraan',
        'status',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }
}
