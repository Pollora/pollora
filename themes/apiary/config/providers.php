<?php

declare(strict_types=1);

use App\Themes\Default\Providers\AssetServiceProvider;

/*
|--------------------------------------------------------------------------
| Theme Service Providers
|--------------------------------------------------------------------------
|
| The service providers specified below will be auto-loaded when a
| request is made to your application. You're welcome to augment this
| list with your custom services to extend your application's features.
|
*/
return [
    \App\Themes\Apiary\Providers\AssetServiceProvider::class,
//    \App\Themes\Apiary\Providers\WordPress\Formatting::class,
];
