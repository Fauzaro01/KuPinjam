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
            'username' => "required|string|max:255|unique:users,username,{$userId},id",
            'no_telp'  => "required|string|unique:users,no_telp,{$userId},id",
        ];
    }
}
