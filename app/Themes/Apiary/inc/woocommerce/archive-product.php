<?php

declare(strict_types=1);

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

// Move the default WooCommerce breadcrumb
Action::remove('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
Action::add('after_header', 'woocommerce_breadcrumb', 1, 0);

/**
 * Override the woocommerce_taxonomy_archive_description function
 */
function woocommerce_taxonomy_archive_description()
{
    if (is_product_taxonomy() && absint(get_query_var('paged')) === 0) {
        $term = get_queried_object();

        if ($term && ! empty($term->description)) {
            $description = wc_format_content(wp_kses_post($term->description));
            echo view('woocommerce.archive.archive-description', ['description' => $description]);
        }
    }
}

/**
 * Override the woocommerce_product_archive_description function
 */
function woocommerce_product_archive_description()
{
    // Don't display the description on search results page.
    if (is_search()) {
        return;
    }

    if (is_post_type_archive('product') && in_array(absint(get_query_var('paged')), [0, 1], true)) {
        $shop_page = get_post(wc_get_page_id('shop'));
        if ($shop_page) {

            $allowed_html = wp_kses_allowed_html('post');

            // This is needed for the search product block to work.
            $allowed_html = array_merge(
                $allowed_html,
                [
                    'form' => [
                        'action' => true,
                        'accept' => true,
                        'accept-charset' => true,
                        'enctype' => true,
                        'method' => true,
                        'name' => true,
                        'target' => true,
                    ],

                    'input' => [
                        'type' => true,
                        'id' => true,
                        'class' => true,
                        'placeholder' => true,
                        'name' => true,
                        'value' => true,
                    ],

                    'button' => [
                        'type' => true,
                        'class' => true,
                        'label' => true,
                    ],

                    'svg' => [
                        'hidden' => true,
                        'role' => true,
                        'focusable' => true,
                        'xmlns' => true,
                        'width' => true,
                        'height' => true,
                        'viewbox' => true,
                    ],
                    'path' => [
                        'd' => true,
                    ],
                ]
            );

            $description = wc_format_content(wp_kses($shop_page->post_content, $allowed_html));
            if ($description) {
                echo view('woocommerce.archive.archive-description', ['description' => $description]);
            }
        }
    }
}

// Product title classes
Filter::add('woocommerce_product_loop_title_classes', function (string $classes) {
    $product_title_class = config('woocommerce.archive-product.product_title.class', 'text-sm font-medium text-gray-900');

    return $product_title_class.' '.$classes;
}, 90);
