<?php

namespace Theme\Providers\WordPress;

use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Action;

class Widgets extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Action::add('after_setup_theme', [$this, 'removeGutenbergThemeSupport'], 99);
    }

    public function removeGutenbergThemeSupport()
    {
        remove_theme_support('block-templates');
        remove_theme_support('widgets-block-editor');

        // remove the theme support for the core-block-patterns
        remove_theme_support('core-block-patterns');

        // remove remote patterns
        add_filter('should_load_remote_block_patterns', '__return_false');
    }
}
