<?php

return [
    'property_id' => env('ANALYTICS_PROPERTY_ID', 'UA-XXXXXXXXX-X'),
    'service_account_credentials_json' => storage_path('app/analytics/service-account-credentials.json'),
    'cache' => [
        'enableCaching' => env('ANALYTICS_CACHE',''),
        'authCache' => env('ANALYTICS_AUTH_CACHE',''),
        'authCacheOptions' => [
            'lifetime' => env('ANALYTICS_CACHE_LIFETIME', 60),
            'prefix' => env('ANA_CACHE_PREFIX', 'analytics_'),
        ],
    ],
];
