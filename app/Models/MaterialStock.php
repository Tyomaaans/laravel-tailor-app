<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialStock extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'supplier_id',
        'category_id',
        'name',
        'quantity',
        'unit',
        'cost_per_unit',
        'min_stock',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'cost_per_unit' => 'decimal:2',
            'min_stock' => 'integer',
        ];
    }

    // Relations
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'category_id');
    }

    // Helpers
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock;
    }

    public function totalValue(): float
    {
        return $this->quantity * $this->cost_per_unit;
    }

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'min_stock');
    }
}
