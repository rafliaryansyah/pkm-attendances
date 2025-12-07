<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting || $setting->value === null) {
                return $default;
            }

            return match ($setting->type) {
                'integer' => (int) $setting->value,
                'decimal' => (float) $setting->value,
                'boolean' => (bool) $setting->value,
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        });
    }

    public static function set(string $key, $value): void
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
            ]);
        }

        Cache::forget("setting_{$key}");
    }

    public static function getAttendanceLocation(): ?array
    {
        $lat = self::get('attendance_location_lat');
        $long = self::get('attendance_location_long');
        $radius = self::get('attendance_radius', 100);
        $name = self::get('attendance_location_name');

        if ($lat === null || $long === null) {
            return null;
        }

        return [
            'lat' => (float) $lat,
            'long' => (float) $long,
            'radius' => (int) $radius,
            'name' => $name,
        ];
    }

    public static function isAttendanceLocationSet(): bool
    {
        return self::getAttendanceLocation() !== null;
    }
}
