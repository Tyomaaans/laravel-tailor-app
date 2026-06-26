<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'nullable|uuid|exists:suppliers,id',
            'category_id' => 'sometimes|uuid|exists:material_categories,id',
            'name' => 'sometimes|string|max:255',
            'quantity' => 'sometimes|numeric|min:0',
            'unit' => 'sometimes|string|max:50',
            'cost_per_unit' => 'sometimes|numeric|min:0',
            'min_stock' => 'nullable|integer|min:0',
        ];
    }
}
