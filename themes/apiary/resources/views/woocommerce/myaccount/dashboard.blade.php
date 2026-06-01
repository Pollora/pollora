{{--
 * My Account Dashboard
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 --}}
@php

    $allowed_html = [
        'a' => [
            'href' => [],
        ],
    ];

    $current_user = wp_get_current_user();
    $icons = config('theme.woocommerce.myaccount.icons', []);
@endphp

{{-- Welcome header --}}
<div class="mb-8">
    <h2 class="text-2xl font-bold text-foreground mt-0">
        @php printf( esc_html__( 'Hello, %s', 'woocommerce' ), esc_html( $current_user->display_name ) ); @endphp
    </h2>
    <p class="mt-1 text-sm text-muted">
        @php
            printf(
                wp_kses( __( 'Not %1$s? <a href="%2$s">Log out</a>', 'woocommerce' ), $allowed_html ),
                '<strong class="font-semibold text-foreground">' . esc_html( $current_user->display_name ) . '</strong>',
                esc_url( wc_logout_url() )
            );
        @endphp
    </p>
</div>

{{-- Quick action cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
    @include('woocommerce.myaccount.dashboard-card', [
        'icon' => 'orders',
        'title' => __('Orders', 'woocommerce'),
        'description' => __('View and track your recent orders', 'woocommerce'),
        'url' => wc_get_endpoint_url('orders'),
    ])
    @include('woocommerce.myaccount.dashboard-card', [
        'icon' => 'edit-address',
        'title' => __('Addresses', 'woocommerce'),
        'description' => __('Manage your billing and shipping addresses', 'woocommerce'),
        'url' => wc_get_endpoint_url('edit-address'),
    ])
    @include('woocommerce.myaccount.dashboard-card', [
        'icon' => 'edit-account',
        'title' => __('Account details', 'woocommerce'),
        'description' => __('Edit your name, email and password', 'woocommerce'),
        'url' => wc_get_endpoint_url('edit-account'),
    ])
    @include('woocommerce.myaccount.dashboard-card', [
        'icon' => 'downloads',
        'title' => __('Downloads', 'woocommerce'),
        'description' => __('Access your downloadable products', 'woocommerce'),
        'url' => wc_get_endpoint_url('downloads'),
    ])
    @if ( WC()->payment_gateways->get_available_payment_gateways() )
        @include('woocommerce.myaccount.dashboard-card', [
            'icon' => 'payment-methods',
            'title' => __('Payment methods', 'woocommerce'),
            'description' => __('Manage your saved payment methods', 'woocommerce'),
            'url' => wc_get_endpoint_url('payment-methods'),
        ])
    @endif
</div>

@php
    do_action( 'woocommerce_account_dashboard' );
    do_action( 'woocommerce_before_my_account' );
    do_action( 'woocommerce_after_my_account' );
@endphp
