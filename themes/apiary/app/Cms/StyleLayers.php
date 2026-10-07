<?php

declare(strict_types=1);

namespace Theme\Apiary\Cms;

use Pollora\Attributes\Action;
use Pollora\Attributes\Filter;

/**
 * Puts WordPress's own CSS in cascade layers, between Tailwind's reset and its utilities.
 *
 * WordPress prints its block library and the global styles built from
 * theme.json outside any layer, and Tailwind keeps its reset and its utilities
 * in layers. Unlayered CSS always wins, so `h2 { font-size }` from theme.json
 * would beat `text-sm` on a heading of the Blade header, and the block
 * library's `:where(.wp-block-button__link) { border-radius: 9999px }` would
 * beat the theme.json button, whatever the specificity.
 *
 * The order becomes theme, base, wp-core (block library), wp (global styles),
 * components, utilities: theme.json styles every Gutenberg block above the
 * reset and above the block library, and a Tailwind class always wins over
 * both. A layer's rank does not depend on where its CSS is printed, which
 * matters since WordPress 7 prints block styles and global styles in the
 * footer of a Blade page.
 *
 * Front end only: the editor has no Tailwind, and theme.json applies there as is.
 */
class StyleLayers
{
    private const string ORDER = '@layer theme, base, wp-core, wp, components, utilities;';

    /**
     * Wrap the inline CSS of WordPress's style handles in their layer.
     */
    #[Action('wp_print_styles', priority: 0)]
    #[Action('wp_print_footer_scripts', priority: 0)]
    public function layerInlineStyles(): void
    {
        if (is_admin()) {
            return;
        }

        foreach (wp_styles()->registered as $handle => $style) {
            $layer = $this->layerFor((string) $handle);

            if ($layer === null || empty($style->extra['after']) || ! empty($style->extra['theme_layered'])) {
                continue;
            }

            $style->extra['after'] = [self::ORDER."\n@layer {$layer} {\n".implode("\n", (array) $style->extra['after'])."\n}"];
            $style->extra['theme_layered'] = true;
        }
    }

    /**
     * Load a linked WordPress stylesheet into its layer instead of as a bare <link>.
     */
    #[Filter('style_loader_tag')]
    public function layerLinkedStyle(string $tag, string $handle, string $href, string $media): string
    {
        $layer = $this->layerFor($handle);

        if ($layer === null || is_admin()) {
            return $tag;
        }

        $mediaQuery = in_array($media, ['', 'all'], true) ? '' : ' '.$media;

        return sprintf(
            "<style id=\"%s-css\">%s\n@import url(\"%s\") layer(%s)%s;</style>\n",
            esc_attr($handle),
            self::ORDER,
            esc_url($href),
            $layer,
            $mediaQuery
        );
    }

    /**
     * The layer a WordPress style handle belongs to, or null to leave it alone.
     *
     * `core-block-supports` (the per-block layout and colours chosen in the
     * editor) stays unlayered on purpose: it must win over the global styles.
     */
    private function layerFor(string $handle): ?string
    {
        if ($handle === 'global-styles') {
            return 'wp';
        }

        if ($handle === 'classic-theme-styles' || str_starts_with($handle, 'wp-block-')) {
            return 'wp-core';
        }

        return null;
    }
}
