{{--
 * Product quantity inputs
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 --}}
@php
	$type     = isset( $type ) ? $type : 'number';
	$readonly = isset( $readonly ) ? $readonly : false;
@endphp
@if ( $max_value && $min_value === $max_value )
	<div class="quantity hidden">
		<input type="hidden" id="{!! esc_attr( $input_id ) !!}" class="qty" name="{!! esc_attr( $input_name ) !!}" value="{!! esc_attr( $min_value ) !!}" />
	</div>
@else
	@php
		$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );
	@endphp
	<div class="quantity inline-flex items-center border border-outline rounded-md">
		@php do_action( 'woocommerce_before_quantity_input_field' ); @endphp
		<label class="sr-only" for="{!! esc_attr( $input_id ) !!}">{!! esc_attr( $label ) !!}</label>
		@if ( ! $readonly )
		<button type="button" class="qty-minus flex items-center justify-center py-2 w-10 h-full text-muted hover:text-foreground transition-colors" aria-label="{{ __('Decrease quantity', 'woocommerce') }}">
			<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
		</button>
		@endif
		<input
			type="{!! esc_attr( $type ) !!}"
			id="{!! esc_attr( $input_id ) !!}"
			class="qty w-14 h-full border-0 text-center text-sm font-medium text-foreground bg-transparent focus:outline-hidden focus:ring-0 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none {!! esc_attr( join( ' ', (array) $classes ) ) !!}"
			step="{!! esc_attr( $step ) !!}"
			min="{!! esc_attr( $min_value ) !!}"
			max="{!! esc_attr( 0 < $max_value ? $max_value : '' ) !!}"
			name="{!! esc_attr( $input_name ) !!}"
			value="{!! esc_attr( $input_value ) !!}"
			title="{!! esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) !!}"
			aria-label="{!! esc_attr__( 'Product quantity', 'woocommerce' ) !!}"
			size="4"
			placeholder="{!! esc_attr( $placeholder ) !!}"
			inputmode="{!! esc_attr( $inputmode ) !!}"
			{!! $readonly ? 'readonly="readonly"' : '' !!} />
		@if ( ! $readonly )
		<button type="button" class="qty-plus flex items-center justify-center w-10 h-full text-muted hover:text-foreground transition-colors" aria-label="{{ __('Increase quantity', 'woocommerce') }}">
			<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
		</button>
		@endif
		@php do_action( 'woocommerce_after_quantity_input_field' ); @endphp
	</div>
@endif
