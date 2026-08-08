<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status_peminjaman',
        'tujuan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id', 'id');
    }

    public function riwayatPengembalian(): HasOne
    {
        return $this->hasOne(RiwayatPengembalian::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status_peminjaman', 'dipinjam')
                     ->where('tanggal_kembali', '>=', Carbon::now());
    }

    public function scopeSelesai($query)
    {
        return $query->where('status_peminjaman', 'selesai');
    }

    public function setTanggalPinjamAttribute($value)
    {
        $this->attributes['tanggal_pinjam'] = Carbon::parse($value);
    }

    public function setTanggalKembaliAttribute($value)
    {
        $this->attributes['tanggal_kembali'] = Carbon::parse($value);
    }

    public function getTanggalPinjamAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function getTanggalKembaliAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}
