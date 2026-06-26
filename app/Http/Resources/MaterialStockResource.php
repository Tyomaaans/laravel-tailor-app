<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialStockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'supplier_id' => $this->supplier_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'cost_per_unit' => $this->cost_per_unit,
            'min_stock' => $this->min_stock,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'materialCategory' => new MaterialCategoryResource($this->whenLoaded('materialCategory')),
        ];
    }
}
