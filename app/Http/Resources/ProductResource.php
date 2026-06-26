<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'base_price' => $this->base_price,
            'unit' => $this->unit,
            'description' => $this->description,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'orderItems' => OrderItemResource::collection($this->whenLoaded('orderItems')),
        ];
    }
}
