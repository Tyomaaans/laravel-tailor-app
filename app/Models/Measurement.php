<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Measurement extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'customer_id',
        'height',
        'weight',
        'neck',
        'chest',
        'waist',
        'hip',
        'shoulder',
        'sleeve_length',
        'shirt_length',
        'pants_length',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'height' => 'integer',
            'weight' => 'integer',
            'neck' => 'integer',
            'chest' => 'integer',
            'waist' => 'integer',
            'hip' => 'integer',
            'shoulder' => 'integer',
            'sleeve_length' => 'integer',
            'shirt_length' => 'integer',
            'pants_length' => 'integer',
        ];
    }

    // Relations
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
