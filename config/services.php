<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'gnews' => [
    'base_url' => env('GNEWS_BASE_URL', 'https://gnews.io/api/v4'),
    'api_key' => env('GNEWS_API_KEY'),
    'language' => env('GNEWS_LANGUAGE', 'en'),
    'country' => env('GNEWS_COUNTRY', 'gb'),
    'category' => env('GNEWS_CATEGORY', 'business'),
    'max' => env('GNEWS_MAX_RESULTS', 10),
],
];
