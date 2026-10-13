<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $guarded = ['id'];

    public const DEFAULTS = [
        'clinic_name' => 'PetCare Clinic',
        'phone' => '+880 1700-000000',
        'emergency_phone' => '+8801700000000',
        'whatsapp' => '8801700000000',
        'email' => 'info@petcareclinic.com',
        'address_en' => 'House 12, Road 5, Gulshan-1, Dhaka',
        'address_bn' => 'বাড়ি ১২, রোড ৫, গুলশান-১, ঢাকা',
        'hours_en' => 'Sat–Thu 9AM–10PM',
        'hours_bn' => 'শনি–বৃহঃ সকাল ৯টা–রাত ১০টা',
        'facebook' => '#', 'instagram' => '#', 'youtube' => '#',
        'map_query' => 'Gulshan 1 Dhaka',
    ];

    public static function all_(): array
    {
        return Cache::rememberForever('settings', function () {
            try {
                return array_merge(self::DEFAULTS, static::query()->pluck('value', 'key')->all());
            } catch (\Throwable) {
                return self::DEFAULTS;
            }
        });
    }

    public static function get(string $key, $default = null)
    {
        return static::all_()[$key] ?? $default;
    }

    /** Bilingual setting with fallback, e.g. Setting::tr('address'). */
    public static function tr(string $key): ?string
    {
        $l = app()->getLocale();
        $o = $l === 'bn' ? 'en' : 'bn';
        return static::get("{$key}_{$l}") ?: static::get("{$key}_{$o}");
    }

    public static function put(array $values): void
    {
        foreach ($values as $k => $v) {
            static::updateOrCreate(['key' => $k], ['value' => $v]);
        }
        Cache::forget('settings');
    }
}
