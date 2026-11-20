<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    protected $table = 'tables';

    protected $fillable = [
        'table_number',
        'capacity',
        'status',
        'qr_code_token',
        'access_code',
    ];

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }

    public function isDirtyTable(): bool
    {
        return $this->status === 'dirty';
    }

    public function isReserved(): bool
    {
        return $this->status === 'reserved';
    }

    public function markAvailable(): bool
    {
        return $this->update(['status' => 'available']);
    }

    public function markDirty(): bool
    {
        return $this->update(['status' => 'dirty']);
    }

    public function markOccupied(): bool
    {
        return $this->update(['status' => 'occupied']);
    }

    public function activeOrder(): ?Order
    {
        return $this->orders->first();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    public function aiConsultations(): HasMany
    {
        return $this->hasMany(AiConsultation::class, 'table_id');
    }
}
