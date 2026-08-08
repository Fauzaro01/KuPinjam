<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID dari route parameter (bisa model atau string ID)
        $userId = $this->route('id') ?? ($this->route('user')?->id ?? $this->route('user'));

        return [
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $userId,
            'no_telp'  => 'required|string|unique:users,no_telp,' . $userId,
            'password' => 'nullable|string|min:8',
            'role'     => 'required|in:karyawan,administrator',
        ];
    }
}
