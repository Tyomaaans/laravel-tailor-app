<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'invoice',
        'customer_id',
        'measurement_id',
        'order_date',
        'due_date',
        'pickup_date',
        'subtotal',
        'discount',
        'total_price',
        'down_payment',
        'remaining_payment',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'due_date' => 'date',
            'pickup_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total_price' => 'decimal:2',
            'down_payment' => 'decimal:2',
            'remaining_payment' => 'decimal:2',
        ];
    }

    // Relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function measurement(): BelongsTo
    {
        return $this->belongsTo(Measurement::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function productionTasks(): HasMany
    {
        return $this->hasMany(ProductionTask::class);
    }

    // Helpers
    public function isPaid(): bool
    {
        return $this->remaining_payment <= 0;
    }

    public function isOverdue(): bool
    {
        return $this->due_date?->isPast() && ! $this->isPickedUp();
    }

    public function isPickedUp(): bool
    {
        return ! is_null($this->pickup_date);
    }

    public function currentStage(): ?ProductionTask
    {
        return $this->productionTasks()
            ->where('status', 'in_progress')
            ->latest()
            ->first();
    }

    public function totalPaid(): float
    {
        return $this->payments()->sum('amount');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->whereNull('pickup_date');
    }

    public function scopeOverdue($query)
    {
        return $query->whereNull('pickup_date')
            ->where('due_date', '<', now());
    }

    public function scopePaid($query)
    {
        return $query->where('remaining_payment', '<=', 0);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('remaining_payment', '>', 0);
    }
}
