{{--
 * Pagination - Show numbered pagination for catalog pages
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 --}}
@php

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
    return;
}
@endphp

<nav class="woocommerce-pagination mt-12 flex items-center justify-center" aria-label="@php esc_attr_e( 'Product Pagination', 'woocommerce' ); @endphp">
    {!! paginate_links( apply_filters( 'woocommerce_pagination_args', [
        'base'         => $base,
        'format'       => $format,
        'add_args'     => false,
        'current'      => max( 1, $current ),
        'total'        => $total,
        'prev_text'    => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>',
        'next_text'    => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
        'type'         => 'list',
        'end_size'     => 3,
        'mid_size'     => 3,
    ] ) ) !!}
</nav>
