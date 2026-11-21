<?php

declare(strict_types=1);

use Pollora\Proxy\WordPressDatabase;
use Pollora\WordPress\DatabaseSettings;

global $wpdb;

// SQLite (development, CI and demos only, DB_CONNECTION=sqlite): the SQLite Database
// Integration plugin translates WordPress's SQL, in the admin too. It replaces the proxy below.
if (config('database.connections.'.config('database.default').'.driver') === 'sqlite') {
    $sqliteFile = DatabaseSettings::sqlitePath((array) config('database.connections.'.config('database.default')));
    $sqliteLoader = __DIR__.'/plugins/sqlite-database-integration/wp-includes/sqlite/db.php';

    if ($sqliteFile === null || ! is_file($sqliteLoader)) {
        wp_die('DB_CONNECTION=sqlite needs the SQLite Database Integration plugin: composer require wp-plugin/sqlite-database-integration (php artisan pollora:doctor).');
    }

    define('DB_ENGINE', 'sqlite');
    define('DB_PATH', $sqliteFile);

    require_once $sqliteLoader;

    return $wpdb;
}

if (
    str_contains($_SERVER['SCRIPT_NAME'], '/cms/wp-admin/')
) {
    return $wpdb;
}

$GLOBALS['wpdb'] = new WordPressDatabase;
