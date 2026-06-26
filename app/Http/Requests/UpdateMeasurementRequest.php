<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes|uuid|exists:customers,id',
            'height' => 'nullable|integer|min:0',
            'weight' => 'nullable|integer|min:0',
            'neck' => 'nullable|integer|min:0',
            'chest' => 'nullable|integer|min:0',
            'waist' => 'nullable|integer|min:0',
            'hip' => 'nullable|integer|min:0',
            'shoulder' => 'nullable|integer|min:0',
            'sleeve_length' => 'nullable|integer|min:0',
            'shirt_length' => 'nullable|integer|min:0',
            'pants_length' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
