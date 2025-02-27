<?php
declare(strict_types=1);

return [
    'app_td' => env('APP_TD'),
    'disallow_file_mods' => true,
    'auth_key' => env('AUTH_KEY'),
    'secure_auth_key' => env('SECURE_AUTH_KEY'),
    'logged_in_key' => env('LOGGED_IN_KEY'),
    'nonce_key' => env('NONCE_KEY'),
    'auth_salt' => env('AUTH_SALT'),
    'secure_auth_salt' => env('SECURE_AUTH_SALT'),
    'logged_in_salt' => env('LOGGED_IN_SALT'),
    'nonce_salt' => env('NONCE_SALT'),
    'wp_allow_multisite' => env('WP_ALLOW_MULTISITE', false),
    'multisite' => env('MULTISITE', false),
    'subdomain_install' => env('SUBDOMAIN_INSTALL', false),
    'domain_current_site' => env('DOMAIN_CURRENT_SITE'),
    'path_current_site' => env('PATH_CURRENT_SITE'),
    'site_id_current_site' => env('SITE_ID_CURRENT_SITE'),
    'blog_id_current_site' => env('BLOG_ID_CURRENT_SITE'),
    'caching' => env('DB_CACHE'),
];
