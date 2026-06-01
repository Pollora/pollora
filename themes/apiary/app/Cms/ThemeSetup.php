<?php

declare(strict_types=1);

namespace Theme\Apiary\Cms;

use Pollora\Attributes\Action;

/**
 * Core theme setup: textdomain, theme supports, etc.
 */
class ThemeSetup
{
    /**
     * Load the theme translation files.
     */
    #[Action('after_setup_theme', priority: 5)]
    public function loadTextdomain(): void
    {
        load_theme_textdomain('apiary', get_template_directory() . '/languages');
    }

    /**
     * Render the Alpine.js toast notification container.
     *
     * Injected via wp_body_open so it's available in all layouts (main, checkout, etc.)
     * without requiring an @include in each layout file.
     *
     * @see resources/views/parts/toast-container.blade.php
     * @see resources/assets/js/frontend/wc-notices.js  Alpine.store('toasts')
     */
    #[Action('wp_body_open')]
    public function renderToastContainer(): void
    {
        echo view('parts.toast-container')->render();
    }
}
