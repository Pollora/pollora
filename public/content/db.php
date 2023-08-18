<?php

declare(strict_types=1);

use Pollen\Proxy\WordPressDatabase;

if (
    str_contains($_SERVER['SCRIPT_NAME'], '/cms/wp-admin/')
) {
    return;
}

return;

$GLOBALS['wpdb'] = new WordPressDatabase;
