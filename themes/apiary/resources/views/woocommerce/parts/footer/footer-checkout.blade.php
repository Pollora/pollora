{{--
 * Checkout footer
 *
 * @package Theme\Apiary\WooCommerce
 --}}
@php
    $privacy_page_id = wc_privacy_policy_page_id();
    $terms_page_id = wc_terms_and_conditions_page_id();
@endphp

<footer class="bg-white border-t border-outline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-muted">
                &copy; {{ date('Y') }} {{ bloginfo('site_title') }}. {{ __('All rights reserved.', 'apiary') }}
            </p>
            <div class="flex items-center gap-4 text-xs text-muted">
                @if ($terms_page_id)
                    <a href="{{ get_permalink( $terms_page_id ) }}" class="hover:text-foreground transition-colors" target="_blank">
                        {{ get_the_title( $terms_page_id ) }}
                    </a>
                @endif
                @if ($privacy_page_id)
                    <a href="{{ get_permalink( $privacy_page_id ) }}" class="hover:text-foreground transition-colors" target="_blank">
                        {{ get_the_title( $privacy_page_id ) }}
                    </a>
                @endif
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3.5 text-success">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    {{ __('Secure payment', 'apiary') }}
                </span>
            </div>
        </div>
    </div>
</footer>
