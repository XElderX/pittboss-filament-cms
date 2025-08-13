<?php

use App\Models\Users;

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'external' => [
            'driver' => 'session',
            'provider' => 'external_users',
        ],
        'merchant' => [
            'driver' => 'session',
            'provider' => 'external_users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'external_users' => [
            'driver' => 'eloquent',
            'model' => Users::class,
        ],
        // 'users' => [
        //     'driver' => 'eloquent',
        //     'model' => App\Models\Users::class,
        // ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'external_users' => [
            'provider' => 'external_users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
