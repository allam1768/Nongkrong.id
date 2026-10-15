<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_available',
        'taste_profile',
    ];

    protected $appends = [
        'image_url',
        'taste_tags',
        'formatted_price',
    ];

    /**
     * Get clean array of taste profile tags.
     *
     * @return list<string>
     */
    public function getTasteTagsAttribute(): array
    {
        if (is_array($this->taste_profile)) {
            return array_values(array_filter($this->taste_profile, fn ($tag) => is_string($tag) && trim($tag) !== ''));
        }

        if (is_string($this->taste_profile)) {
            return array_values(array_filter(array_map('trim', explode(',', $this->taste_profile)), fn ($tag) => $tag !== ''));
        }

        return [];
    }

    /**
     * Get formatted Indonesian Rupiah price string.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
            'taste_profile' => 'array',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset($this->image);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
