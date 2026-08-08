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
            'email'    => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')->withoutTrashed(),
            ],
            'no_telp'  => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('users', 'no_telp')->withoutTrashed(),
            ],
            'password' => 'required|string|min:8',
            'role'     => 'required|in:karyawan,administrator',
        ];
    }
}
