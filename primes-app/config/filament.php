<?php

return [
    'path' => 'admin',
    'middleware' => [
        'web',
        'auth',
    ],
    'auth' => [
        'guard' => 'web',
    ],
    'domain' => env('APP_URL', 'https://proyecto-final-primes-production-96c3.up.railway.app'),
    'url' => env('APP_URL', 'https://proyecto-final-primes-production-96c3.up.railway.app'),
    'assets' => [
        'preload' => [
            'styles' => true,
            'scripts' => true,
        ],
        'url' => env('APP_URL', 'https://proyecto-final-primes-production-96c3.up.railway.app'),
    ],
    'layout' => [
        'sidebar' => [
            'is_collapsible_on_desktop' => true,
            'groups' => [
                'are_collapsible' => true,
            ],
        ],
    ],
    'default_filesystem_disk' => 'public',
];
