<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKendaraanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plat_nomor'      => 'required|string|max:20|unique:kendaraans,plat_nomor',
            'merk'            => 'required|string|max:50',
            'model'           => 'required|string|max:50',
            'tahun'           => 'required|integer|between:1900,' . date('Y'),
            'jenis_kendaraan' => 'required|in:mobil,motor',
        ];
    }
}
