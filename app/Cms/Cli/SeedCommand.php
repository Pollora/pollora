<?php

declare(strict_types=1);

namespace App\Cms\Cli;

use Pollora\Attributes\WpCli;
use Pollora\Attributes\WpCli\Command;

/**
 * Example WP-CLI command — discovered automatically by Pollora.
 *
 * Uses the #[WpCli] attribute on the class and #[Command] on methods.
 * This class appears in the Pollora dashboard under "WP-CLI Commands".
 *
 * Usage: wp starter-seed create --count=5
 */
#[WpCli('starter-seed')]
class SeedCommand
{
    /**
     * Create sample projects.
     *
     * ## OPTIONS
     *
     * [--count=<count>]
     * : Number of projects to create.
     * ---
     * default: 5
     * ---
     *
     * ## EXAMPLES
     *
     *     wp starter-seed create --count=10
     */
    #[Command]
    public function create(array $args, array $assocArgs): void
    {
        $count = (int) ($assocArgs['count'] ?? 5);

        for ($i = 1; $i <= $count; $i++) {
            wp_insert_post([
                'post_type' => 'project',
                'post_title' => sprintf('Sample Project %d', $i),
                'post_status' => 'publish',
                'post_content' => 'This is a sample project created by the seed command.',
            ]);
        }

        \WP_CLI::success(sprintf('Created %d sample projects.', $count));
    }

    /**
     * Clean all sample projects.
     *
     * ## EXAMPLES
     *
     *     wp starter-seed clean
     */
    #[Command]
    public function clean(array $args, array $assocArgs): void
    {
        $posts = get_posts([
            'post_type' => 'project',
            'posts_per_page' => -1,
            'post_status' => 'any',
        ]);

        foreach ($posts as $post) {
            wp_delete_post($post->ID, true);
        }

        \WP_CLI::success(sprintf('Deleted %d projects.', count($posts)));
    }
}
