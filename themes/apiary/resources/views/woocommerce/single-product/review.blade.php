{{-- *
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
  --}}
{{--  Exit if accessed directly --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


if ( ! defined( 'ABSPATH' ) ) {
    exit; }
@endphp
<li {!! comment_class('flex text-sm text-gray-500 space-x-4'); !!} id="li-comment-{!! comment_ID() !!}">

    <div id="comment-@php comment_ID(); @endphp" class="flex text-sm text-gray-500 space-x-4 comment_container">

        {{-- *
         * The woocommerce_review_before hook
         *
         * @hooked woocommerce_review_display_gravatar - 10
          --}}
        @php
          do_action( 'woocommerce_review_before', $comment );
        @endphp

        <div class="py-10 comment-text">

            {{-- *
             * The woocommerce_review_comment_text hook
             *
             * @hooked woocommerce_review_display_comment_text - 10
              --}}
            {{-- *
             * The woocommerce_review_meta hook.
             *
             * @hooked woocommerce_review_display_meta - 10
              --}}
            {{-- *
             * The woocommerce_review_before_comment_meta hook.
             *
             * @hooked woocommerce_review_display_rating - 10
              --}}
            @php
                do_action( 'woocommerce_review_before_comment_meta', $comment );
                do_action( 'woocommerce_review_meta', $comment );
                do_action( 'woocommerce_review_before_comment_text', $comment );
                do_action( 'woocommerce_review_comment_text', $comment );
                do_action( 'woocommerce_review_after_comment_text', $comment );
            @endphp

        </div>
    </div>
