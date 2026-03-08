<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Settings extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    protected $table = 'settings';
    /**
     * Get a setting value by key, with optional default.
     *
     * Usage:  Setting::get('store_name', 'My Store')
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $row = static::where('key', $key)->first();

        if (! $row) return $default;

        return static::cast($row->value, $row->type);
    }

    /**
     * Set (upsert) a setting value.
     *
     * Usage:  Setting::set('store_name', 'New Name')
     */
    public static function set(string $key, mixed $value, string $type = 'string', string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
    }

    /**
     * Get all settings for a group as key => value array.
     *
     * Usage:  Setting::group('store')
     */
    public static function group(string $group): array
    {
        return static::where('group', $group)
            ->get()
            ->mapWithKeys(fn($row) => [$row->key => static::cast($row->value, $row->type)])
            ->toArray();
    }

    /**
     * Bulk upsert an array of  key => value  pairs.
     */
    public static function setMany(array $data, string $group = 'general'): void
    {
        foreach ($data as $key => $value) {
            $existing = static::where('key', $key)->first();
            $type     = $existing?->type ?? 'string';
            static::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $existing?->group ?? $group]
            );
        }
    }

    /** Cast raw DB string to the correct PHP type */
    private static function cast(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => (bool)(int) $value,
            'integer' => (int) $value,
            'json'    => json_decode($value, true),
            default   => $value,
        };
    }
}