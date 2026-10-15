<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $order_code
 * @property int|null $table_id
 * @property string $customer_name
 * @property int|null $handled_by
 * @property string $order_status
 * @property string $payment_status
 * @property string $payment_method
 * @property float $total_amount
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Table|null $table
 * @property-read User|null $handledBy
 * @property-read Collection<int, OrderItem> $items
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'table_id',
        'customer_name',
        'handled_by',
        'order_status',
        'payment_status',
        'payment_method',
        'snap_token',
        'total_amount',
        'notes',
    ];

    protected $appends = [
        'formatted_total_amount',
    ];

    public function getFormattedTotalAmountAttribute(): string
    {
        return 'Rp '.number_format((float) $this->total_amount, 0, ',', '.');
    }

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
