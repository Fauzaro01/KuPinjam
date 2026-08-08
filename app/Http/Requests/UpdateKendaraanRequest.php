<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKendaraanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kendaraanId = $this->route('kendaraan')?->id ?? $this->route('kendaraan');

        return [
            'plat_nomor'      => 'required|string|max:20|unique:kendaraans,plat_nomor,' . $kendaraanId,
            'merk'            => 'required|string|max:50',
            'model'           => 'required|string|max:50',
            'tahun'           => 'required|integer|between:1900,' . date('Y'),
            'jenis_kendaraan' => 'required|in:mobil,motor',
            'status'          => 'required|in:tersedia,dipinjam,perawatan',
        ];
    }
}
