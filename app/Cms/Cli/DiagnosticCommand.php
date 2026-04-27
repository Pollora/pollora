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
}
