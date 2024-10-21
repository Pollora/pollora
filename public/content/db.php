<?php

declare(strict_types=1);

use Pollora\Proxy\WordPressDatabase;

global $wpdb;
if (
    str_contains($_SERVER['SCRIPT_NAME'], '/cms/wp-admin/')
) {
    return $wpdb;
}

$GLOBALS['wpdb'] = new WordPressDatabase;

