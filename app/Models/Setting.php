<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    public const KEY_RESTAURANT_NAMES = 'reservation.restaurant_name';
    public const KEY_CURRENCIES = 'reservation.currency';

    public const OPTION_KEYS = [
        self::KEY_RESTAURANT_NAMES => 'Restaurant names',
        self::KEY_CURRENCIES => 'Currencies',
    ];

    protected $fillable = [
        'meta_key',
        'meta_value',
    ];

    public function scopeForKey(Builder $query, string $key): Builder
    {
        return $query->where('meta_key', $key);
    }

    public static function values(string $key): Collection
    {
        $values = self::query()
            ->forKey($key)
            ->orderBy('meta_value')
            ->pluck('meta_value');

        return $values->isNotEmpty()
            ? $values
            : collect(self::defaultValues($key));
    }

    public static function defaultValues(string $key): array
    {
        return match ($key) {
            self::KEY_RESTAURANT_NAMES => [
                'Main Restaurant',
            ],
            self::KEY_CURRENCIES => [
                'USD',
            ],
            default => [],
        };
    }

    public function setMetaKeyAttribute(?string $value): void
    {
        $this->attributes['meta_key'] = trim((string) $value);
    }

    public function setMetaValueAttribute(?string $value): void
    {
        $this->attributes['meta_value'] = trim((string) $value);
    }
}