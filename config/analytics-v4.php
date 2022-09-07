<?php

// config for Myoutdeskllc/LaravelAnalyticsV4
return [
    'property_id' => config('analytics_property_id'),
    'service_account_credentials_json' => storage_path('app/analytics/service-account-credentials.json'),
    'cache_lifetime_in_minutes' => 60 * 24,
    'enableCaching' => config('analytics_cache'),
    'authCache' => null,
    'authCacheOptions' => [
        'lifetime' => config('analytics_cache_lifetime'),
        'prefix' => config('analytics_cache_prefix'),
    ],
];
