<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductionTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'sometimes|uuid|exists:orders,id',
            'assigned_to' => 'nullable|uuid|exists:users,id',
            'stage' => 'sometimes|in:cutting,sewing,finishing,quality_check,ready',
            'status' => 'sometimes|in:pending,in_progress,done,revision',
            'started_at' => 'nullable|date',
            'finished_at' => 'nullable|date|after_or_equal:started_at',
            'notes' => 'nullable|string',
        ];
    }
}
