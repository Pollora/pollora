<?php

declare(strict_types=1);

namespace App\Cms\Cli;

use Pollora\Attributes\WpCli;
use Pollora\Attributes\WpCli\Command;

/**
 * Test case: WP-CLI command with multiple subcommands.
 *
 * Validates that the WP-CLI discovery properly registers all public methods
 * as subcommands, and that the #[Command] attribute overrides the default slug.
 *
 * @covers Pollora\WpCli\Infrastructure\Services\WpCliDiscovery
 * @covers Pollora\Attributes\WpCli
 * @covers Pollora\Attributes\WpCli\Command
 *
 * Usage:
 *   wp pollora-diag status
 *   wp pollora-diag check-hooks
 *   wp pollora-diag check-schedules
 *   wp pollora-diag check-post-types
 */
#[WpCli('pollora-diag')]
class DiagnosticCommand
{
    /**
     * Display overall framework status.
     *
     * ## EXAMPLES
     *
     *     wp pollora-diag status
     */
    #[Command]
    public function status(array $args, array $assocArgs): void
    {
        \WP_CLI::log('Pollora Framework Diagnostic');
        \WP_CLI::log('============================');
        \WP_CLI::log(sprintf('PHP Version: %s', PHP_VERSION));
        \WP_CLI::log(sprintf('WordPress Version: %s', get_bloginfo('version')));
        \WP_CLI::log(sprintf('Laravel Version: %s', app()->version()));
        \WP_CLI::log(sprintf('Theme: %s', get_stylesheet()));

        \WP_CLI::success('Diagnostic complete.');
    }

    /**
     * Check discovered hooks.
     *
     * ## EXAMPLES
     *
     *     wp pollora-diag check-hooks
     */
    #[Command('check-hooks')]
    public function checkHooks(array $args, array $assocArgs): void
    {
        $expectedHooks = [
            'after_setup_theme' => 'ThemeHooks::setupTheme',
            'wp_enqueue_scripts' => 'ThemeHooks::enqueueAssets',
            'wp_head' => 'ContentHooks::addMetaGenerator',
        ];

        foreach ($expectedHooks as $hook => $description) {
            $hasHook = has_action($hook);
            $status = $hasHook ? '✓' : '✗';
            \WP_CLI::log(sprintf('  %s %s (%s)', $status, $hook, $description));
        }

        \WP_CLI::success('Hook check complete.');
    }

    /**
     * Check discovered schedules.
     *
     * ## EXAMPLES
     *
     *     wp pollora-diag check-schedules
     */
    #[Command('check-schedules')]
    public function checkSchedules(array $args, array $assocArgs): void
    {
        $schedules = wp_get_schedules();

        \WP_CLI::log('Registered schedules:');

        foreach ($schedules as $key => $schedule) {
            \WP_CLI::log(sprintf(
                '  - %s: %s (every %d seconds)',
                $key,
                $schedule['display'],
                $schedule['interval']
            ));
        }

        \WP_CLI::success(sprintf('Found %d schedules.', count($schedules)));
    }

    /**
     * Check discovered post types.
     *
     * ## EXAMPLES
     *
     *     wp pollora-diag check-post-types
     */
    #[Command('check-post-types')]
    public function checkPostTypes(array $args, array $assocArgs): void
    {
        $customTypes = get_post_types(['_builtin' => false], 'objects');

        \WP_CLI::log('Custom post types:');

        foreach ($customTypes as $type) {
            \WP_CLI::log(sprintf(
                '  - %s: %s (archive: %s, rest: %s)',
                $type->name,
                $type->label,
                $type->has_archive ? 'yes' : 'no',
                $type->show_in_rest ? 'yes' : 'no'
            ));
        }

        \WP_CLI::success(sprintf('Found %d custom post types.', count($customTypes)));
    }

    /**
     * Check registered routes and template hierarchy.
     *
     * Lists all Route::wp() routes with their WordPress conditions,
     * verifies the fallback FrontendController is in place, and checks
     * that each route's target Blade view exists.
     *
     * ## OPTIONS
     *
     * [--format=<format>]
     * : Output format.
     * ---
     * default: table
     * options:
     *   - table
     *   - json
     * ---
     *
     * ## EXAMPLES
     *
     *     wp pollora-diag check-routes
     *     wp pollora-diag check-routes --format=json
     *
     * @covers Pollora\Route\Infrastructure\Providers\RouteServiceProvider
     * @covers Pollora\Route\Domain\Models\Route
     * @covers Pollora\Route\UI\Http\Controllers\FrontendController
     */
    #[Command('check-routes')]
    public function checkRoutes(array $args, array $assocArgs): void
    {
        $router = app('router');
        $routes = $router->getRoutes();

        $wpRoutes = [];
        $laravelRoutes = [];
        $hasFallback = false;
        $errors = 0;

        \WP_CLI::log('Routing Diagnostic');
        \WP_CLI::log('==================');
        \WP_CLI::log('');

        // Analyze all registered routes
        foreach ($routes as $route) {
            if (method_exists($route, 'isWordPressRoute') && $route->isWordPressRoute()) {
                $condition = method_exists($route, 'getCondition') ? $route->getCondition() : '?';
                $params = method_exists($route, 'getConditionParameters') ? $route->getConditionParameters() : [];
                $action = $route->getActionName();

                $wpRoutes[] = [
                    'condition' => $condition,
                    'parameters' => $params ? implode(', ', $params) : '-',
                    'action' => $action,
                ];
            } else {
                $uri = $route->uri();
                $action = $route->getActionName();

                // Detect the fallback route (catches all unmatched requests)
                if ($uri === '{any}' || str_contains($action, 'FrontendController')) {
                    $hasFallback = true;
                }

                $laravelRoutes[] = [
                    'uri' => $uri,
                    'methods' => implode('|', $route->methods()),
                    'action' => $action,
                ];
            }
        }

        // Display WordPress routes
        \WP_CLI::log(sprintf('WordPress Routes (Route::wp): %d registered', count($wpRoutes)));
        \WP_CLI::log('');

        if ($wpRoutes) {
            $format = $assocArgs['format'] ?? 'table';
            \WP_CLI\Utils\format_items($format, $wpRoutes, ['condition', 'parameters', 'action']);
        } else {
            \WP_CLI::warning('No Route::wp() routes found.');
            $errors++;
        }

        \WP_CLI::log('');

        // Display Laravel routes (excluding WP routes)
        \WP_CLI::log(sprintf('Laravel Routes: %d registered', count($laravelRoutes)));
        \WP_CLI::log('');

        if ($laravelRoutes) {
            $format = $assocArgs['format'] ?? 'table';
            \WP_CLI\Utils\format_items($format, $laravelRoutes, ['methods', 'uri', 'action']);
        }

        \WP_CLI::log('');

        // Check fallback controller
        $fallbackStatus = $hasFallback ? '✓' : '✗';
        \WP_CLI::log(sprintf('  %s FrontendController fallback (template hierarchy)', $fallbackStatus));

        if (! $hasFallback) {
            \WP_CLI::warning('FrontendController fallback not detected — template hierarchy may not work.');
            $errors++;
        }

        \WP_CLI::log('');

        // Check that Blade views exist for template hierarchy templates
        \WP_CLI::log('Template Hierarchy Views:');

        $templateViews = [
            'home' => 'Homepage (is_home)',
            'page' => 'Page (is_page)',
            'post' => 'Single post (is_singular, post)',
            '404' => '404 page (is_404)',
            'archive' => 'Archive (is_archive)',
            'category' => 'Category (is_category)',
            'search' => 'Search (is_search)',
            'author' => 'Author (is_author)',
            'single-project' => 'Single project (is_singular, project)',
            'taxonomy' => 'Taxonomy (is_tax)',
        ];

        foreach ($templateViews as $view => $description) {
            $exists = view()->exists($view);
            $status = $exists ? '✓' : '✗';
            \WP_CLI::log(sprintf('  %s %s → %s', $status, $view, $description));

            if (! $exists) {
                $errors++;
            }
        }

        \WP_CLI::log('');

        if ($errors > 0) {
            \WP_CLI::warning(sprintf('Route check complete with %d issue(s).', $errors));
        } else {
            \WP_CLI::success('Route check complete — all routes and views are properly configured.');
        }
    }
}
