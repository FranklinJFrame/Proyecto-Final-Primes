<?php

return [
    'path' => 'admin',
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
    'auth' => [
        'guard' => 'web',
    ],
    'domain' => env('APP_URL', 'https://proyecto-final-primes-production-96c3.up.railway.app'),
    'url' => env('APP_URL', 'https://proyecto-final-primes-production-96c3.up.railway.app'),
];
