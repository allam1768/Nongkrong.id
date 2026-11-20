<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting || $setting->value === null) {
            return $default;
        }

        $decoded = json_decode($setting->value, true);

        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $setting->value;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): static
    {
        $payload = (is_array($value) || is_object($value) || is_bool($value))
            ? json_encode($value)
            : (string) $value;

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $payload]
        );
    }

    /**
     * Delete a setting key.
     */
    public static function forget(string $key): bool
    {
        return (bool) static::where('key', $key)->delete();
    }
}
