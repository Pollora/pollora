{{-- *
 * Single Product Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
  --}}
{{--  Exit if accessed directly. --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php

    global $product;

    if ( ! wc_review_ratings_enabled() ) {
        return;
    }

    $rating_count = $product->get_rating_count();
    $review_count = $product->get_review_count();
    $average      = $product->get_average_rating();
@endphp

@if ( $rating_count > 0 )
    <div class="woocommerce-product-rating flex items-center gap-2 mt-1">
        {!! wc_get_rating_html( $average, $rating_count ) !!}
        @if ( comments_open() )
            <a href="#reviews" class="text-sm font-medium text-muted hover:text-primary transition-colors woocommerce-review-link" rel="nofollow">
                {{ $review_count }} {{ _n('review', 'reviews', $review_count, 'woocommerce') }}
            </a>
        @endif
    </div>
@endif
