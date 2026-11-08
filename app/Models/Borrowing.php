<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Borrowing extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tool_id',
        'quantity',
        'technician_id',
        'recorded_by',
        'returned_by',
        'borrowed_at',
        'returned_at',
        'return_condition',
        'borrowing_notes',
        'return_notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'borrowed_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function isActive(): bool
    {
        return $this->returned_at === null;
    }
}
