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
        return [
            'name' => 'sometimes|string|max:255',
            'email' => "sometimes|email|unique:users,email,{$this->route('user')}",
            'password' => 'sometimes|string|min:8',
            'phone' => 'sometimes|string|max:50',
            'address' => 'sometimes|string',
            'role' => 'sometimes|in:admin,sales,tailor,production,manager',
        ];
    }
}
