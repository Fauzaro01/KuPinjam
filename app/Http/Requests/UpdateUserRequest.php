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
            'email'    => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')
                    ->ignore($userId)
                    ->withoutTrashed(),
            ],
            'no_telp'  => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('users', 'no_telp')
                    ->ignore($userId)
                    ->withoutTrashed(),
            ],
            'password' => 'nullable|string|min:8',
            'role'     => 'required|in:karyawan,administrator',
        ];
    }
}
