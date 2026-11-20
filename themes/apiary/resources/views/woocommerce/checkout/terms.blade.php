{{--
 * Checkout terms and conditions area.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Theme\Apiary\WooCommerce
 * @version 3.4.0
 --}}
@if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) )
@php
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );
@endphp

<div class="woocommerce-terms-and-conditions-wrapper border-t border-outline mt-4 [&_.woocommerce-privacy-policy-text]:mt-0! [&_.woocommerce-privacy-policy-text]:pt-4! [&_.woocommerce-privacy-policy-text]:text-xs! [&_.woocommerce-privacy-policy-text]:text-subtle!">
		{{-- *
		 * Terms and conditions hook used to inject content.
		 *
		 * @since 3.4.0.
		 * @hooked wc_checkout_privacy_policy_text() Shows custom privacy policy text. Priority 20.
		 * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
		  --}}
		@php
			do_action( 'woocommerce_checkout_terms_and_conditions' );
		@endphp

		@if ( wc_terms_and_conditions_checkbox_enabled() )
			<div class="form-row validate-required mt-6 flex space-x-2">
				<div class="flex items-center h-5">
					<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox h-4 w-4 rounded border-outline text-foreground focus:ring-foreground/20" name="terms" @php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); @endphp id="terms" />
				</div>
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox text-xs text-muted">
					<span class="woocommerce-terms-and-conditions-checkbox-text">@php wc_terms_and_conditions_checkbox_text(); @endphp</span>&nbsp;<span class="required">*</span>
				</label>
				<input type="hidden" name="terms-field" value="1" />
			</div>
		@endif
	</div>
	@php
		do_action( 'woocommerce_checkout_after_terms_and_conditions' );
	@endphp
@endif
