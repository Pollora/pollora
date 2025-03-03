@extends('layouts.app')

@section('content')
    @php
        do_action('woocommerce_before_main_content')
    @endphp
    <header class="woocommerce-products-header border-b border-gray-200 pt-24 pb-10">
        @if(apply_filters('woocommerce_show_page_title', true))
            <h1 class="woocommerce-products-header__title page-title text-4xl font-extrabold tracking-tight text-gray-900">{{ woocommerce_page_title(false) }}</h1>
        @endif
        @php
            do_action('woocommerce_archive_description')
        @endphp
    </header>
    <div class="product-grid-controls col-start-1 row-start-1 py-4">
        <div class="mx-auto flex max-w-7xl justify-end items-center gap-x-4 px-4 sm:px-6 lg:px-8">
            <button type="button" x-description="Mobile filter dialog toggle, controls the 'mobileFilterDialogOpen' state." class="inline-flex items-center lg:hidden" @click="open = true">
                <span class="text-sm font-medium text-gray-700">Filters</span>
                <svg class="ml-1 size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"></path>
                </svg>
            </button>
            @php
                do_action('woocommerce_before_shop_loop')
            @endphp
        </div>
    </div>
    <div class="pt-12 pb-24 lg:grid lg:grid-cols-3 lg:gap-x-8 xl:grid-cols-4">
        @include('woocommerce.archive.sidebar')
        <div class="mt-6 lg:mt-0 lg:col-span-2 xl:col-span-3">

            @php
                woocommerce_product_loop_start();

        if ( wc_get_loop_prop( 'total' ) ) {
            while ( have_posts() ) {
                the_post();

                /**
                 * Hook: woocommerce_shop_loop.
                 */
                do_action( 'woocommerce_shop_loop' );

                wc_get_template_part( 'content', 'product' );
            }
        }

        woocommerce_product_loop_end();
            @endphp
            @php
                do_action('woocommerce_after_shop_loop')
           @endphp
        </div>
    </div>

    @php
        do_action('woocommerce_after_main_content')
    @endphp
@endsection
