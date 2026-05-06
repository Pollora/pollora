{{-- *
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); @endphp

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" @php do_action( 'woocommerce_edit_account_form_tag' ); @endphp >
	@php do_action( 'woocommerce_edit_account_form_start' ); @endphp
	<div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
		<div class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			<label for="account_first_name" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'First name', 'woocommerce' ); @endphp&nbsp;<span class="required">*</span></label>
			<div class="mt-1">
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text shadow-xs focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="account_first_name" id="account_first_name" autocomplete="given-name" value="{!! esc_attr( $user->first_name ) !!}" />
			</div>
		</div>
		<div class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			<label for="account_last_name" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Last name', 'woocommerce' ); @endphp&nbsp;<span class="required">*</span></label>
			<div class="mt-1">
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text shadow-xs focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="account_last_name" id="account_last_name" autocomplete="family-name" value="{!! esc_attr( $user->last_name ) !!}" />
			</div>
		</div>

		<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="account_display_name" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Display name', 'woocommerce' ); @endphp&nbsp;<span class="required">*</span></label>
			<div class="mt-1">
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text shadow-xs focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="account_display_name" id="account_display_name" value="{!! esc_attr( $user->display_name ) !!}" />
			</div>
			<p class="mt-2 text-xs text-gray-500">
				<em>@php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); @endphp</em>
			</p>
		</div>

		<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="account_email" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Email address', 'woocommerce' ); @endphp&nbsp;<span class="required">*</span></label>
			<div class="mt-1">
				<input type="email" class="woocommerce-Input woocommerce-Input--email input-text shadow-xs focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="account_email" id="account_email" autocomplete="email" value="{!! esc_attr( $user->user_email ) !!}" />
			</div>
		</div>
	</div>

	<fieldset class="mt-10">
		<legend class="text-lg leading-6 font-medium text-gray-900">
			@php esc_html_e( 'Password change', 'woocommerce' ); @endphp
		</legend>
		<div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
			<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_current" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); @endphp</label>
				<div class="mt-1">
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text shadow-xs focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="password_current" id="password_current" autocomplete="off" />
				</div>
			</div>
		</div>
		<div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
			<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_1" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); @endphp</label>
				<div class="mt-1">
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text shadow-xs focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="password_1" id="password_1" autocomplete="off" />
				</div>
			</div>
			<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_2" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Confirm new password', 'woocommerce' ); @endphp</label>
				<div class="mt-1">
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text shadow-xs focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="password_2" id="password_2" autocomplete="off" />
				</div>
			</div>
		</div>
	</fieldset>

	@php do_action( 'woocommerce_edit_account_form' ); @endphp

	<div class="py-6 sm:flex sm:items-center sm:justify-end">
		@php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); @endphp
		<button type="submit" class="woocommerce-Button button w-full bg-indigo-600 border border-transparent rounded-md shadow-xs py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 sm:ml-6 sm:order-last sm:w-auto" name="save_account_details" value="@php esc_attr_e( 'Save changes', 'woocommerce' ); @endphp">@php esc_html_e( 'Save changes', 'woocommerce' ); @endphp</button>
		<input type="hidden" name="action" value="save_account_details" />
	</div>

	@php do_action( 'woocommerce_edit_account_form_end' ); @endphp
</form>

@php do_action( 'woocommerce_after_edit_account_form' ); @endphp
