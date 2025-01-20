@extends('layouts.main')

@section('content')
    {{--
        /**
		 * woocommerce_before_main_content hook.

		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
    --}}
    @php
        do_action('woocommerce_before_main_content')
    @endphp

    @loop
        @php
            wc_get_template_part( 'content', 'single-product' );
        @endphp
    @endloop
{{--
    /**
     * woocommerce_after_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
--}}
    @php
        do_action('woocommerce_after_main_content')
    @endphp
@endsection()