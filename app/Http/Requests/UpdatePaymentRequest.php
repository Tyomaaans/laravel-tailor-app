<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'sometimes|uuid|exists:orders,id',
            'amount' => 'sometimes|numeric|min:0',
            'method' => 'sometimes|in:cash,transfer,qris,other',
            'paid_at' => 'sometimes|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }
}
