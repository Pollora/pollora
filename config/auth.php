<?php

declare(strict_types=1);
use Pollora\Models\User;

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
            'model' => User::class,
        ],
    ],

];
