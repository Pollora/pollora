{{--
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.0.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@if ( $max_value && $min_value === $max_value )
	<div class="quantity hidden">
		<input type="hidden" id="{!! esc_attr( $input_id ) !!}" class="qty" name="{!! esc_attr( $input_name ) !!}" value="{!! esc_attr( $min_value ) !!}" />
	</div>
	{{--  translators: %s: Quantity.  --}}
@else
	@php
		$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );
	@endphp
	<div class="quantity">
		@php do_action( 'woocommerce_before_quantity_input_field' ); @endphp
		<label class="screen-reader-text" for="{!! esc_attr( $input_id ) !!}">{!! esc_attr( $label ) !!}</label>
		<input
			type="number"
			id="{!! esc_attr( $input_id ) !!}"
			class="h-full box-border text-base shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md {!! esc_attr( join( ' ', (array) $classes ) ) !!}"
			step="{!! esc_attr( $step ) !!}"
			min="{!! esc_attr( $min_value ) !!}"
			max="{!! esc_attr( 0 < $max_value ? $max_value : '' ) !!}"
			name="{!! esc_attr( $input_name ) !!}"
			value="{!! esc_attr( $input_value ) !!}"
			title="{!! esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) !!}"
			size="4"
			placeholder="{!! esc_attr( $placeholder ) !!}"
			inputmode="{!! esc_attr( $inputmode ) !!}" />
		@php do_action( 'woocommerce_after_quantity_input_field' ); @endphp
	</div>
@endif
