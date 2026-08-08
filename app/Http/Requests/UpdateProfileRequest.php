<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'username' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('users', 'username')->ignore($userId)->withoutTrashed(),
            ],
            'no_telp'  => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('users', 'no_telp')->ignore($userId)->withoutTrashed(),
            ],
        ];
    }
}
