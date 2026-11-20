{{-- WooCommerce store notice --}}
@if (is_store_notice_showing())
    <div class="bg-foreground">
        <div class="max-w-7xl mx-auto h-10 px-4 flex items-center justify-center sm:px-6 lg:px-8">
            <p class="text-center text-sm font-medium text-white w-full">
                {!! get_option('woocommerce_demo_store_notice') !!}
            </p>
        </div>
    </div>
@endif
