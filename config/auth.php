<?php

declare(strict_types=1);

return [

    'guards' => [
        'web' => [
            'driver' => 'wp',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'wp',
            'model' => \Pollora\Models\User::class,
        ],
    ],

];
