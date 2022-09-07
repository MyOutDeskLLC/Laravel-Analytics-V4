<?php

return [
    'property_id' => config('analytics_property_id'),
    'service_account_credentials_json' => storage_path('app/analytics/service-account-credentials.json'),
    'cache' => [
        'enableCaching' => config('analytics_cache'),
        'authCache' => null,
        'authCacheOptions' => [
            'lifetime' => config('analytics_cache_lifetime'),
            'prefix' => config('analytics_cache_prefix'),
        ]
    ]
];
