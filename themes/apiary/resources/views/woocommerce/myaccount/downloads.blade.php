{{--
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 --}}
@php

    $icons = config('theme.woocommerce.myaccount.icons', []);
    $downloads     = WC()->customer->get_downloadable_products();
    $has_downloads = (bool) $downloads;

    do_action( 'woocommerce_before_account_downloads', $has_downloads );
@endphp

@if ( $has_downloads )
    @php do_action( 'woocommerce_before_available_downloads' ); @endphp
    @php do_action( 'woocommerce_available_downloads', $downloads ); @endphp
    @php do_action( 'woocommerce_after_available_downloads' ); @endphp
@else
    {{-- Empty state --}}
    <div class="rounded-xl border border-dashed border-outline bg-surface/50 py-12 text-center">
        @if (!empty($icons['empty-downloads']))
            <span class="flex justify-center text-subtle">{!! $icons['empty-downloads'] !!}</span>
        @endif
        <h3 class="mt-4 text-sm font-semibold text-foreground">@php esc_html_e( 'No downloads available yet.', 'woocommerce' ); @endphp</h3>
        <p class="mt-1 text-sm text-muted">@php esc_html_e( 'Your downloadable products will appear here after purchase.', 'woocommerce' ); @endphp</p>
        <div class="mt-6">
            <a href="{{ esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) }}"
               class="inline-flex items-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover transition-colors">
                @php esc_html_e( 'Browse products', 'woocommerce' ); @endphp
            </a>
        </div>
    </div>
@endif

@php do_action( 'woocommerce_after_account_downloads', $has_downloads ); @endphp
