<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'key',
        'value',
        'group',
        'label',
        'type',
    ];

    protected $casts = [
        'key' => 'string',
        'value' => 'string',
        'group' => 'string',
        'label' => 'string',
        'type' => 'string',
    ];

    /**
     * Get a setting value by key, with optional default
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        // Handle boolean
        if ($setting->type === 'boolean') {
            return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
        }

        // Handle number
        if ($setting->type === 'number') {
            return is_numeric($setting->value) ? (float) $setting->value : $default;
        }

        return $setting->value;
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]);
    }

    /**
     * Scope by group
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
