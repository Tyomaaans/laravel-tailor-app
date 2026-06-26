<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice' => $this->invoice,
            'customer_id' => $this->customer_id,
            'measurement_id' => $this->measurement_id,
            'order_date' => $this->order_date,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'total_price' => $this->total_price,
            'down_payment' => $this->down_payment,
            'remaining_payment' => $this->remaining_payment,
            'due_date' => optional($this->due_date)?->toIso8601String(),
            'pickup_date' => optional($this->pickup_date)?->toIso8601String(),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'measurement' => new MeasurementResource($this->whenLoaded('measurement')),
            'orderItems' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'productionTasks' => ProductionTaskResource::collection($this->whenLoaded('productionTasks')),
        ];
    }
}
