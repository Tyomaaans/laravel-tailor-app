<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionTask extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_id',
        'assigned_to',
        'stage',
        'status',
        'started_at',
        'finished_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    const STAGES = [
        'cutting' => 'Pemotongan',
        'sewing' => 'Menjahit',
        'finishing' => 'Finishing',
        'quality_check' => 'Quality Check',
        'ready' => 'Siap Ambil',
    ];

    const STATUSES = [
        'pending' => 'Menunggu',
        'in_progress' => 'Dikerjakan',
        'done' => 'Selesai',
        'revision' => 'Revisi',
    ];

    // Relations
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Helpers
    public function stageLabel(): string
    {
        return self::STAGES[$this->stage] ?? $this->stage;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function duration(): ?int
    {
        if (! $this->started_at || ! $this->finished_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->finished_at);
    }

    public function start(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function finish(): void
    {
        $this->update([
            'status' => 'done',
            'finished_at' => now(),
        ]);
    }

    // Scopes
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeByStage($query, string $stage)
    {
        return $query->where('stage', $stage);
    }
}
