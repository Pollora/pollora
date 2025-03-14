{{-- *
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.4
  --}}
{{--  @codingStandardsIgnoreLine. --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
<div class="py-4 max-w-xl">
	<form class="checkout_coupon woocommerce-form-coupon" method="post">
		<div class="flex-none bg-gray-50 border-t border-gray-200 p-6">
			<label for="coupon_code" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Coupon code', 'woocommerce' ); @endphp</label>
			<div class="flex space-x-4 mt-1">
				<input type="text" name="coupon_code" id="coupon_code" class="input-text block w-full border-gray-300 rounded-md shadow-xs focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="@php esc_attr_e( 'Coupon code', 'woocommerce' ); @endphp" id="coupon_code" value="" />
				<button type="submit" class="button bg-gray-200 text-sm font-medium text-gray-600 rounded-md px-4 hover:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500" name="apply_coupon" value="@php esc_attr_e( 'Apply coupon', 'woocommerce' ); @endphp">@php esc_html_e( 'Apply', 'woocommerce' ); @endphp</button>
			</div>
		</div>
	</form>
</div>
