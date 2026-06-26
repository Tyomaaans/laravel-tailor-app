<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice' => 'required|string|max:255|unique:orders,invoice',
            'customer_id' => 'required|uuid|exists:customers,id',
            'measurement_id' => 'nullable|uuid|exists:measurements,id',
            'order_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:order_date',
            'pickup_date' => 'nullable|date',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'remaining_payment' => 'required|numeric|min:0',
        ];
    }
}
