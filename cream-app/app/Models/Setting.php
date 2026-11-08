<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    protected const CACHE_KEY = 'cream.settings';

    /**
     * Get a setting value by dot-free key, with a fallback default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = Cache::rememberForever(self::CACHE_KEY, function () {
            return self::query()->pluck('value', 'key')->all();
        });

        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    /**
     * Create or update a setting and bust the cache.
     */
    public static function put(string $key, mixed $value): void
    {
        self::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        $bust = fn () => Cache::forget(self::CACHE_KEY);
        static::saved($bust);
        static::deleted($bust);
    }
}
