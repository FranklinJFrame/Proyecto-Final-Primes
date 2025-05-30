<?php

return [
    'asset_url' => env('ASSET_URL', env('APP_URL')),
    'domain' => env('FILAMENT_DOMAIN'),
    'home_url' => env('FILAMENT_HOME_URL', '/'),'
return [
    'asset_url' => env('ASSET_URL', env('APP_URL')),
    'domain' => env('FILAMENT_DOMAIN'),
    'home_url' => env('FILAMENT_HOME_URL', '/'),
    'path' => env('FILAMENT_PATH', 'admin'),
    'middleware' => [
        'web',
        'auth',
    ],
    'assets' => [
        'preload' => [
            'styles' => true,
            'scripts' => true,
        ],
    ],
];
