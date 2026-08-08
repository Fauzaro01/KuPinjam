<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'no_telp'  => 'required|string|unique:users,no_telp',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:karyawan,administrator',
        ];
    }
}
