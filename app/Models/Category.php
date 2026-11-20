<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Determine if this category represents beverages/drinks.
     */
    public function isBeverage(): bool
    {
        $foodSlugs = ['pastry-bakery', 'meals-snacks', 'food', 'foods', 'snack', 'snacks', 'bakery', 'pastry', 'makanan'];
        if (in_array($this->slug, $foodSlugs, true)) {
            return false;
        }

        $foodKeywords = ['pastry', 'bakery', 'meal', 'snack', 'makanan', 'toast', 'croissant', 'roti'];
        $name = strtolower($this->name);
        foreach ($foodKeywords as $kw) {
            if (str_contains($name, $kw)) {
                return false;
            }
        }

        return true;
    }
}
