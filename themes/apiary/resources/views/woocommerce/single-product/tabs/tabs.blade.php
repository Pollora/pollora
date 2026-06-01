{{-- *
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
  --}}
{{-- *
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https: * @package WooCommerce\Templates
 * @version 9.8.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php




$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
@endphp
@if ( ! empty( $product_tabs ) )


	<div class="w-full">
		<div x-data="Components.tabs()" @tab-click.window="onTabClick" @tab-keydown.window="onTabKeydown">
			<div class="border-b border-outline">
				<div class="-mb-px flex space-x-8" aria-orientation="horizontal" role="tablist">
					@foreach ( $product_tabs as $key => $product_tab )
						<button id="tab-title-{!! esc_attr( $key ) !!}" class="{!! esc_attr( $key ) !!}_tab whitespace-nowrap py-6 border-b-2 font-medium text-sm border-transparent text-muted hover:text-foreground hover:border-outline" :class="{ 'border-primary text-primary': selected, 'border-transparent text-muted hover:text-foreground hover:border-outline': !(selected) }" x-data="Components.tab(0)" aria-controls="tab-title-{!! esc_attr( $key ) !!}" role="tab" x-init="init()" @click="onClick" @keydown="onKeydown" @tab-select.window="onTabSelect" :tabindex="selected ? 0 : -1" :aria-selected="selected ? 'true' : 'false'" type="button" tabindex="-1" aria-selected="false">
							{!! wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ) !!}
						</button>
					@endforeach
				</div>
			</div>
			@foreach ( $product_tabs as $key => $product_tab )
				<div id="tab-{!! esc_attr( $key ) !!}" class="-mb-10" x-description="'{{ $product_tab['title'] }}' panel, show/hide based on tab state" x-data="Components.tabPanel(0)" aria-labelledby="tab-title-{!! esc_attr( $key ) !!}" x-init="init()" x-show="selected" @tab-select.window="onTabSelect" role="tabpanel" tabindex="0">
					@if ( isset( $product_tab['callback'] ) )
						{!! call_user_func( $product_tab['callback'], $key, $product_tab ) !!}
					@endif
				</div>
			@endforeach
		</div>
		@php do_action( 'woocommerce_product_after_tabs' ); @endphp
	</div>


@endif

