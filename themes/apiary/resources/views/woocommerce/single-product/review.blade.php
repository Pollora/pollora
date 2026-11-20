{{-- *
 * Review Comments Template
 *
 * Closing li is left out on purpose!
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
  --}}
@php
@endphp
<li {!! comment_class('rounded-xl border border-outline bg-white p-5'); !!} id="li-comment-{!! comment_ID() !!}">

    <div id="comment-@php comment_ID(); @endphp" class="comment_container">
        <div class="flex items-start gap-4">
            {{-- Avatar --}}
            @php do_action( 'woocommerce_review_before', $comment ); @endphp

            <div class="flex-1 min-w-0">
                {{-- Meta: name + date + verified --}}
                @php do_action( 'woocommerce_review_meta', $comment ); @endphp

                {{-- Stars --}}
                @php do_action( 'woocommerce_review_before_comment_meta', $comment ); @endphp
                @php do_action( 'woocommerce_review_before_comment_text', $comment ); @endphp

                {{-- Comment text --}}
                <div class="mt-2 text-sm text-foreground leading-relaxed">
                    @php do_action( 'woocommerce_review_comment_text', $comment ); @endphp
                </div>

                @php do_action( 'woocommerce_review_after_comment_text', $comment ); @endphp
            </div>
        </div>
    </div>
