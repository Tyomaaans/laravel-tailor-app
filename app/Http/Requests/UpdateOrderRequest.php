<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice' => 'sometimes|string|max:255|unique:orders,invoice,$this->route('order')',
            'customer_id' => 'sometimes|uuid|exists:customers,id',
            'measurement_id' => 'nullable|uuid|exists:measurements,id',
            'order_date' => 'sometimes|date',
            'due_date' => 'nullable|date|after_or_equal:order_date',
            'pickup_date' => 'nullable|date',
            'subtotal' => 'sometimes|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_price' => 'sometimes|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'remaining_payment' => 'sometimes|numeric|min:0',
        ];
    }
}
