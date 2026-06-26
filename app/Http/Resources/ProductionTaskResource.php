<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'assigned_to' => $this->assigned_to,
            'stage' => $this->stage,
            'status' => $this->status,
            'notes' => $this->notes,
            'started_at' => optional($this->started_at)?->toIso8601String(),
            'finished_at' => optional($this->finished_at)?->toIso8601String(),
            'order' => new OrderResource($this->whenLoaded('order')),
            'assignee' => new UserResource($this->whenLoaded('assignee')),
        ];
    }
}
