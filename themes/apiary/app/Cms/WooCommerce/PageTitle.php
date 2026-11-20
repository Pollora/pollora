<?php

declare(strict_types=1);

namespace Theme\Apiary\Cms\WooCommerce;

use Pollora\Attributes\Action;
use Pollora\Attributes\Filter;

/**
 * Controls page title visibility for WooCommerce pages.
 *
 * Filters:
 *   - `theme/page/default_show_title` — default visibility when no meta is stored.
 *   - `theme/page/show_title`         — final visibility when meta `_hide_page_title` is present.
 *
 * Admin metabox: checkbox "Hide page title" on page editor, pre-checked
 * based on the `theme/page/default_show_title` filter value.
 */
class PageTitle
{
    private const META_KEY = '_hide_page_title';

    /**
     * Hide the page title by default on core WooCommerce pages (cart, checkout, my-account).
     */
    #[Filter('theme/page/default_show_title', priority: 10)]
    public function hideOnWooCommercePages(bool $show, int $postId): bool
    {
        if (! function_exists('wc_get_page_id')) {
            return $show;
        }

        $wcPageIds = array_filter([
            wc_get_page_id('cart'),
            wc_get_page_id('checkout'),
            wc_get_page_id('myaccount'),
        ]);

        if (in_array($postId, $wcPageIds, true)) {
            return false;
        }

        return $show;
    }

    /**
     * Register the "Hide page title" metabox on page editor screens.
     */
    #[Action('add_meta_boxes_page', priority: 10)]
    public function registerMetaBox(): void
    {
        add_meta_box(
            'apiary_hide_page_title',
            __('Page title', 'apiary'),
            [$this, 'renderMetaBox'],
            'page',
            'side',
            'default'
        );
    }

    /**
     * Render the metabox checkbox.
     *
     * When no meta is stored yet, the checkbox state reflects the filter default.
     */
    public function renderMetaBox(\WP_Post $post): void
    {
        $meta = get_post_meta($post->ID, self::META_KEY, true);

        if ($meta !== '') {
            $checked = (bool) $meta;
        } else {
            // No meta stored — derive from the default filter
            $checked = ! apply_filters('theme/page/default_show_title', true, $post->ID);
        }

        wp_nonce_field('apiary_hide_page_title', 'apiary_hide_page_title_nonce');
        printf(
            '<label><input type="checkbox" name="%s" value="1" %s /> %s</label>',
            esc_attr(self::META_KEY),
            checked($checked, true, false),
            esc_html__('Hide the page title (H1)', 'apiary')
        );
    }

    /**
     * Save the metabox value.
     *
     * Always stores a value ("1" or "0") so the meta becomes the source of truth
     * once the user has explicitly interacted with the checkbox.
     */
    #[Action('save_post_page', priority: 10)]
    public function saveMetaBox(int $postId): void
    {
        if (
            ! isset($_POST['apiary_hide_page_title_nonce'])
            || ! wp_verify_nonce($_POST['apiary_hide_page_title_nonce'], 'apiary_hide_page_title')
        ) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (! current_user_can('edit_page', $postId)) {
            return;
        }

        $value = isset($_POST[self::META_KEY]) ? '1' : '0';
        update_post_meta($postId, self::META_KEY, $value);
    }
}
