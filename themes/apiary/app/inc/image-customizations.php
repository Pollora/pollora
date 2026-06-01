<?php

declare(strict_types=1);

namespace Theme\Apiary;

/**
 * Image display customizations (figure wrapping, captions, custom logo).
 *
 * - Wraps standalone `<img>` tags in `<figure class="media">` elements.
 * - Overrides the `[caption]` shortcode to use semantic `<figure>/<figcaption>`.
 * - Provides a `custom_logo()` helper for rendering the site logo with configurable classes.
 *
 * @package Theme\Apiary
 */

/**
 * Wrap standalone images (and linked images) in `<figure>` tags.
 *
 * Replaces `<p><img></p>` with `<figure class="media"><img></figure>`.
 *
 * @see https://interconnectit.com/2175/how-to-remove-p-tags-from-images-in-wordpress/
 */
add_filter('the_content', function (string $pee): string {
    return preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '<figure class="media">$1</figure>', $pee);
}, 50);

/**
 * Override the default caption shortcode with semantic `<figure>/<figcaption>` markup.
 *
 * @see https://devpress.com/blog/captions-in-wordpress/
 */
add_filter('img_caption_shortcode', function (string $output, array $attr, string $content): string {
    if (is_feed()) {
        return $output;
    }

    $attr = shortcode_atts([
        'id'      => '',
        'align'   => 'alignnone',
        'width'   => '',
        'caption' => '',
    ], $attr);

    if (1 > $attr['width'] || empty($attr['caption'])) {
        return $content;
    }

    return sprintf(
        '<figure class="media %s">%s<figcaption class="media__caption">%s</figcaption></figure>',
        esc_attr($attr['align']),
        do_shortcode($content),
        $attr['caption']
    );
}, 10, 3);

/**
 * Render the custom logo with configurable image and link classes.
 *
 * @param  string  $imgClasses   CSS classes applied to the `<img>` element.
 * @param  string  $linkClasses  CSS classes applied to the wrapping `<a>` element.
 * @param  bool    $enableLink   Whether to wrap the logo in a link to the homepage.
 * @return string HTML markup for the logo.
 */
function custom_logo(string $imgClasses = 'w-8 h-8', string $linkClasses = 'custom-logo-link', bool $enableLink = true): string
{
    $customLogoId = get_theme_mod('custom_logo');
    $image = wp_get_attachment_image($customLogoId, 'full', false, [
        'class' => $imgClasses,
    ]);

    if (! $enableLink) {
        return $image;
    }

    return sprintf(
        '<a href="%s" class="%s" rel="home" itemprop="url">%s</a>',
        esc_url(home_url()),
        esc_attr($linkClasses),
        $image
    );
}