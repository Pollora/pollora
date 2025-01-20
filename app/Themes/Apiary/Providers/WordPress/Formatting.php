<?php

namespace App\Themes\Apiary\Providers\WordPress;

use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Filter;

class Formatting extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filter::add('sanitize_html_class', [$this, 'sanitizeHtmlClasses'], 99, 3);
    }

    /**
     * Fix for Tailwind CSS Classes
     *
     * @param  string  $class
     */
    public function sanitizeHtmlClasses(string $sanitized, $class, string $fallback): string
    {
        // Strip out any %-encoded octets.
        $sanitized = preg_replace('|%[a-fA-F0-9][a-fA-F0-9]|', '', $class);

        // Limit to A-Z, a-z, :, 0-9, '_', '-'.
        $sanitized = preg_replace('/[^A-Za-z0-9\:_-]/', '', $sanitized);

        if ('' === $sanitized && $fallback) {
            return sanitize_html_class($fallback);
        }
        /**
         * Filters a sanitized HTML class string.
         *
         * @param  string  $sanitized The sanitized HTML class.
         * @param  string  $class HTML class before sanitization.
         * @param  string  $fallback The fallback string.
         *
         * @since 2.8.0
         */
        return $sanitized;
    }
}
