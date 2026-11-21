<?php

declare(strict_types=1);

/*
 * Overrides of the Pollora framework's WordPress configuration (config/wordpress.php of
 * pollora/framework): only the keys written here replace the framework's defaults.
 */
return [

    /*
     * Laravel session on Route::wp() routes.
     *
     * Off by default in this skeleton: with it on, every anonymous visitor starts a session (a row in the
     * `sessions` table and two cookies), which writes to the database on each first request and keeps a
     * page cache from storing the response. This skeleton has no Laravel form, no @csrf and no flash data
     * on its WordPress routes, and WordPress authentication, nonces and WooCommerce do not use the session.
     *
     * Set it to true if your project adds a Laravel form (@csrf), old() or flash data on a Route::wp() route,
     * or Laravel-side authentication there. Laravel-native routes keep their session either way.
     */
    'sessions' => false,

];
