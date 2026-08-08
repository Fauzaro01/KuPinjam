<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'              => 'required|exists:users,id',
            'kendaraan_id'         => 'required|exists:kendaraans,id',
            // Tidak menggunakan after_or_equal:today agar admin bisa edit peminjaman lampau
            'tanggal_pinjam'       => 'required|date',
            'tanggal_kembali'      => 'required|date|after:tanggal_pinjam',
            'tujuan'               => 'required|string|max:255',
            'keterangan'           => 'nullable|string',
            'status_peminjaman'    => 'nullable|in:dipinjam,selesai,terlambat',
            'admin_notes'          => 'nullable|string',
        ];
    }
}
