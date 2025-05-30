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
];
