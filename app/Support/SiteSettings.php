<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettings
{
    public static function get(): ?SiteSetting
    {
        return Cache::remember('site_settings:first_row', 60 * 60, function () {
            return SiteSetting::query()->first();
        });
    }

    public static function value(string $key, $default = null)
    {
        $settings = self::get();
        return $settings?->{$key} ?? $default;
    }

    public static function clearCache(): void
    {
        Cache::forget('site_settings:first_row');
    }
}
