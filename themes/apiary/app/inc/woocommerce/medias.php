<?php

use Pollora\Support\Facades\Filter;

// Override the single image size
Filter::add('woocommerce_get_image_size_single', function ($size) {
    return [
        'width' => 696,
        'height' => '',
        'crop' => 0,
    ];
});

/**
 * Override the woocommerce_get_product_thumbnail function
 *
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\Factory|\Illuminate\View\View|string|\Pollora\Core\Application
 */
function woocommerce_get_product_thumbnail(string $size = 'woocommerce_thumbnail', int $deprecated1 = 0, int $deprecated2 = 0)
{
    global $product;

    $image_size = apply_filters('single_product_archive_thumbnail_size', $size);
    $args['class'] = config('woocommerce.archive-product.product_thumbnail.class', 'w-full h-full object-center object-cover sm:w-full sm:h-full');
    $thumbnail = $product->get_image($image_size, $args);

    return $product ? view('woocommerce.archive.product-image', ['thumbnail' => $thumbnail]) : '';
}

// Product thumbnail sizing
Filter::add('woocommerce_checkout_item_thumbnail', fn ($html) => str_replace('attachment-woocommerce_thumbnail', 'attachment-woocommerce_thumbnail '.config('woocommerce.checkout.product_thumbnail.class', 'w-20 h-20 rounded-md object-center object-cover'), $html));
Filter::add('woocommerce_thank_you_item_thumbnail', fn ($html) => str_replace('attachment-woocommerce_thumbnail', 'attachment-woocommerce_thumbnail '.config('woocommerce.thank_you.product_thumbnail.class', 'flex-none w-20 h-20 object-center object-cover bg-gray-100 rounded-lg sm:w-40 sm:h-40'), $html));
Filter::add('woocommerce_cart_item_thumbnail', fn ($html) => str_replace('attachment-woocommerce_thumbnail', 'attachment-woocommerce_thumbnail'.config('woocommerce.cart.product_thumbnail.class', 'w-24 h-24 rounded-md object-center object-cover sm:w-48 sm:h-48'), $html));
