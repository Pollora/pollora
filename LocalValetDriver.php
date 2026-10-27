<?php

declare(strict_types=1);

use Valet\Drivers\LaravelValetDriver;

/**
 * Valet and Herd driver for a Pollora project.
 *
 * Both send every `.php` URL to the front controller, so WordPress's own entry
 * points (wp-login.php, wp-admin/*.php, wp-cron.php, xmlrpc.php) never ran:
 * they answered the site's 404. This driver hands them to PHP as a web server
 * with a document root does.
 */
class LocalValetDriver extends LaravelValetDriver
{
    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if (str_ends_with($path, '/')) {
            $path .= 'index.php';
        }

        $script = realpath($sitePath.'/public'.rawurldecode($path));
        $public = realpath($sitePath.'/public');

        // Never run a PHP file a visitor or a plugin put in the uploads folder
        $inUploads = $script !== false && str_contains($script, DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR);

        if ($script !== false && $public !== false && ! $inUploads
            && str_starts_with($script, $public.DIRECTORY_SEPARATOR)
            && str_ends_with($script, '.php') && is_file($script)) {
            $_SERVER['SCRIPT_FILENAME'] = $script;
            $_SERVER['SCRIPT_NAME'] = $path;
            $_SERVER['PHP_SELF'] = $path;
            $_SERVER['DOCUMENT_ROOT'] = $public;

            return $script;
        }

        return parent::frontControllerPath($sitePath, $siteName, $uri);
    }
}
