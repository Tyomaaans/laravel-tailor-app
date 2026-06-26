<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeasurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'height' => $this->height,
            'weight' => $this->weight,
            'neck' => $this->neck,
            'chest' => $this->chest,
            'waist' => $this->waist,
            'hip' => $this->hip,
            'shoulder' => $this->shoulder,
            'sleeve_length' => $this->sleeve_length,
            'shirt_length' => $this->shirt_length,
            'pants_length' => $this->pants_length,
            'notes' => $this->notes,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
        ];
    }
}
