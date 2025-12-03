<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function setValue(string $key, $value, string $type = 'string', string $group = 'general', string $description = null): void
    {
        $setting = self::firstOrNew(['key' => $key]);
        
        $setting->value = is_array($value) ? json_encode($value) : (string) $value;
        $setting->type = $type;
        $setting->group = $group;
        $setting->description = $description;
        
        $setting->save();
    }
}
