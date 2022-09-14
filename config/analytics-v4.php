<?php

return [
    'property_id' => env('ANA_PROPERTY_ID', 'XXXXXXXXX'),
    'service_account_credentials_json' => storage_path('app/analytics/service-account-credentials.json'),
    'cache' => [
        'enableCaching' => env('ANA_CACHE',false),
        'authCache' =>null,
        'authCacheOptions' => [
            'lifetime' => env('ANA_CACHE_LIFETIME', 60),
            'prefix' => env('ANA_CACHE_PREFIX', 'analytics_'),
        ],
    ],
];
