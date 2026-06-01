<?php

declare(strict_types=1);

namespace Theme\Apiary\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Asset;
use Pollora\Support\Facades\Filter;

/**
 * Registers theme assets and injects runtime configuration into the page.
 *
 * Handles three concerns:
 * - Enqueues the Vite-built JS/CSS bundle on the frontend.
 * - Outputs `window.apiaryCart` (cart URLs, i18n, add-to-cart modal data)
 *   and `window.apiarySearch` (search suggestion endpoint and settings)
 *   as inline `<script>` blocks in `wp_footer`.
 * - Dequeues WooCommerce's default stylesheets so the theme controls all styling.
 *
 * @see config('theme.woocommerce.single-product.add_to_cart_confirmation') Modal toggle
 * @see config('theme.woocommerce.search') Search suggestions settings
 */
class AssetServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Asset::add('apiary/script', 'app.js')
            ->container('theme')
            ->toFrontend()
            ->useVite();

        // Localize runtime data via wp_footer (after all scripts are registered)
        Action::add('wp_footer', function (): void {
            $confirmationEnabled = (bool) config('theme.woocommerce.single-product.add_to_cart_confirmation.enabled', false);

            $data = [
                'addToCartConfirmation' => $confirmationEnabled,
                'cartUrl'     => function_exists('wc_get_cart_url') ? wc_get_cart_url() : '',
                'checkoutUrl' => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '',
                'i18n' => [
                    'addedToCart'      => __('%s added to cart.', 'apiary'),
                    'productAdded'     => __('Product added to cart.', 'apiary'),
                    'dismiss'          => __('Dismiss', 'apiary'),
                    'addedToCartTitle' => __('Added to cart', 'apiary'),
                    'viewCart'         => __('View cart', 'apiary'),
                    'continueShopping' => __('Continue shopping', 'apiary'),
                    'checkout'         => __('Checkout', 'apiary'),
                    'recentlyViewed'   => __('Recently viewed', 'apiary'),
                    'youMayAlsoLike'   => __('You may also like', 'apiary'),
                ],
            ];

            if ($confirmationEnabled && function_exists('is_product') && is_product()) {
                $product = wc_get_product(get_the_ID());
                if ($product) {
                    $data['currentProduct'] = [
                        'id'           => $product->get_id(),
                        'name'         => $product->get_name(),
                        'price'        => $product->get_price_html(),
                        'image'        => wp_get_attachment_url($product->get_image_id()) ?: wc_placeholder_img_src(),
                        'description'  => $product->get_short_description(),
                        'url'          => get_permalink(),
                        'crossSellIds' => $product->get_cross_sell_ids(),
                        'upsellIds'    => $product->get_upsell_ids(),
                    ];
                }
            }

            echo '<script>window.apiaryCart = ' . wp_json_encode($data) . ';</script>' . "\n";

            // Search suggestions config
            $searchConfig = config('theme.woocommerce.search', []);
            if (! empty($searchConfig['suggestions'])) {
                $searchData = [
                    'apiUrl'     => home_url('/api/products/search'),
                    'minChars'   => (int) ($searchConfig['min_chars'] ?? 3),
                    'debounce'   => (int) ($searchConfig['debounce'] ?? 300),
                    'maxResults' => (int) ($searchConfig['max_results'] ?? 6),
                ];
                echo '<script>window.apiarySearch = ' . wp_json_encode($searchData) . ';</script>' . "\n";
            }
        }, 1);

        // Remove default WooCommerce styles
        Filter::add('woocommerce_enqueue_styles', function (array $enqueueStyles) {
            unset($enqueueStyles['woocommerce-general'], $enqueueStyles['woocommerce-layout'], $enqueueStyles['woocommerce-smallscreen']);
            return $enqueueStyles;
        });
    }
}
