<?php

return [
    'asset_url' => 'https://proyecto-final-primes-production-96c3.up.railway.app',
    'domain' => 'proyecto-final-primes-production-96c3.up.railway.app',
    'home_url' => '/',
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
