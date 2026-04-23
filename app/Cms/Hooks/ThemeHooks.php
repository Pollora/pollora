<?php

declare(strict_types=1);

namespace App\Cms\Hooks;

use Pollora\Attributes\Action;
use Pollora\Attributes\Filter;

/**
 * Example hook class — discovered automatically by Pollora.
 *
 * Actions and filters are registered via PHP attributes.
 * This class appears in the Pollora dashboard under "Hooks".
 */
class ThemeHooks
{
    #[Action('after_setup_theme')]
    public function setupTheme(): void
    {
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    }

    #[Action('wp_enqueue_scripts')]
    public function enqueueAssets(): void
    {
        // Enqueue theme styles and scripts here
    }

    #[Filter('excerpt_length')]
    public function customExcerptLength(int $length): int
    {
        return 30;
    }

    #[Filter('upload_mimes')]
    public function allowSvgUpload(array $mimes): array
    {
        $mimes['svg'] = 'image/svg+xml';

        return $mimes;
    }
}
