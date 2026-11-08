<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tool extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'rack_location',
        'total_quantity',
        'maintenance_quantity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_quantity' => 'integer',
            'maintenance_quantity' => 'integer',
        ];
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class)->whereNull('returned_at');
    }

    public function activeBorrowing(): HasOne
    {
        return $this->hasOne(Borrowing::class)->whereNull('returned_at');
    }

    public function getActiveBorrowingsCountAttribute(): int
    {
        return $this->relationLoaded('activeBorrowings')
            ? $this->activeBorrowings->count()
            : $this->activeBorrowings()->count();
    }

    public function getActiveBorrowingsQuantityAttribute(): int
    {
        if (isset($this->attributes['active_borrowings_sum_quantity'])) {
            return (int) $this->attributes['active_borrowings_sum_quantity'];
        }

        return $this->relationLoaded('activeBorrowings')
            ? (int) $this->activeBorrowings->sum('quantity')
            : (int) $this->activeBorrowings()->sum('quantity');
    }

    public function getAvailableQuantityAttribute(): int
    {
        if ($this->status !== 'available') {
            return 0;
        }

        $borrowed = $this->active_borrowings_quantity;
        $maintenance = (int) ($this->maintenance_quantity ?? 0);

        return max(0, $this->total_quantity - $borrowed - $maintenance);
    }

    public function isAvailable(): bool
    {
        return $this->available_quantity > 0;
    }
}
