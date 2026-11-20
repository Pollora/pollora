{{-- *
 * The template to display the reviewers meta data (name, verified owner, review date)
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
  --}}
@php
    global $comment;
    $verified = wc_review_is_from_verified_owner( $comment->comment_ID );
@endphp

@if ( '0' === $comment->comment_approved )
    <p class="meta">
        <em class="woocommerce-review__awaiting-approval text-xs text-muted italic">
            {!! esc_html_e( 'Your review is awaiting approval', 'woocommerce' ) !!}
        </em>
    </p>
@else
    <div class="flex items-center gap-2 flex-wrap">
        <span class="woocommerce-review__author text-sm font-semibold text-foreground">@php comment_author(); @endphp</span>
        @if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified )
            <span class="woocommerce-review__verified inline-flex items-center gap-1 rounded-full bg-success-light px-2 py-0.5 text-xs font-medium text-success">
                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 0 0 0-5.304 3 3 0 0 0-3.75-3.751 3 3 0 0 0-5.305 0 3 3 0 0 0-3.751 3.75 3 3 0 0 0 0 5.305 3 3 0 0 0 3.75 3.751 3 3 0 0 0 5.305 0 3 3 0 0 0 3.751-3.75Zm-2.546-4.46a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>
                {{ __('Verified', 'woocommerce') }}
            </span>
        @endif
        <span class="text-subtle">&middot;</span>
        <time class="woocommerce-review__published-date text-xs text-subtle" datetime="{!! esc_attr( get_comment_date( 'c' ) ) !!}">
            {!! esc_html( get_comment_date( wc_date_format() ) ) !!}
        </time>
    </div>
@endif
