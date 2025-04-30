<?php

namespace Theme\View;

use Illuminate\Support\Str;
use Theme\Core\Filesystem;
use function view;

class WpView
{
    protected FileViewFinder $fileFinder;

    protected ViewFinder $viewFinder;

    public function __construct()
    {
        $this->fileFinder = $this->getFileFinder();
        $this->viewFinder = new ViewFinder(
            $this->fileFinder,
            new Filesystem(),
            get_stylesheet_directory()
        );
    }

    /**
     * Get the theme file finder
     *
     *
     * @throws \Illuminate\Container\EntryNotFoundException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getFileFinder(): FileViewFinder
    {
        $viewPaths = [];
        foreach (config('theme.views') as $view) {
            $viewPaths[] = get_stylesheet_directory().DS.$view;
        }

        return new FileViewFinder(app('files'), $viewPaths);
    }

    /**
     * Main template include blade support
     */
    public function templateInclude(string $template): string
    {
        if (! $this->isWooCommerceTemplate($template)) {
            return $template;
        }

        return $this->locateThemeTemplate($template) ?: $template;
    }

    /**
     * WooCommerce Comment and Reviews template support
     */
    public function reviewsTemplate(string $template): string
    {
        if (! $this->isWooCommerceTemplate($template)) {
            return $template;
        }

        return $this->template($template);
    }

    /**
     * Filter a template path, taking into account theme templates and creating
     * blade loaders as needed.
     */
    public function template(string $template): string
    {
        // Locate any matching template within the theme.
        $themeTemplate = $this->locateThemeTemplate($template);

        // Return filename for status screen
        if (
            is_admin() &&
            ! wp_doing_ajax() &&
            get_current_screen() &&
            get_current_screen()->id === 'woocommerce_page_wc-status'
        ) {
            return $themeTemplate;
        }

        // Include directly unless it's a blade file.
        if (! Str::endsWith($themeTemplate, '.blade.php')) {
            return $themeTemplate === '' ? $template : $themeTemplate;
        }

        // We have a template, create a loader file and return it's path.
        return view(
            $this->fileFinder->getPossibleViewNameFromPath(realpath($themeTemplate))
        )->makeLoader();
    }

    /**
     * Check if template is a WooCommerce template.
     */
    protected function isWooCommerceTemplate(string $template): bool
    {
        return $this->relativeTemplatePath($template) !== $template;
    }

    /**
     * Return the theme relative template path.
     */
    protected function relativeTemplatePath(string $template): string
    {
        $defaultPaths = [
            // WooCommerce plugin templates
            \WC_ABSPATH.'templates/',
        ];

        if (is_child_theme()) {
            // Parent theme templates in woocommerce/ subfolder.
            $defaultPaths[] = get_template_directory().'/'.WC()->template_path();
        }

        return str_replace(
            apply_filters('sage-woocommerce/templates', $defaultPaths),
            '',
            $template
        );
    }

    /**
     * Locate the theme's WooCommerce blade template when available.
     */
    protected function locateThemeTemplate(string $template): string
    {
        // Absolute plugin template path -> woocommerce/single-product.php
        $themeTemplate = WC()->template_path().$this->relativeTemplatePath($template);
        // Return absolute theme template path.
        return locate_template($this->viewFinder->locate($themeTemplate));
    }
}
