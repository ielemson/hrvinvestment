<?php

if (!function_exists('money_format_app')) {
    function money_format_app($amount): string
    {
        $settings = \App\Models\SiteSetting::query()->first();

        $symbol     = $settings->currency_symbol ?? '$';
        $position   = $settings->currency_position ?? 'before'; // before | after
        $decimals   = (int)($settings->decimal_places ?? 2);
        $thousand   = $settings->thousand_separator ?? ',';
        $decimal    = $settings->decimal_separator ?? '.';

        $amount = is_numeric($amount) ? (float)$amount : 0;

        $formatted = number_format($amount, $decimals, $decimal, $thousand);

        return $position === 'after'
            ? "{$formatted} {$symbol}"
            : "{$symbol} {$formatted}";
    }
}
