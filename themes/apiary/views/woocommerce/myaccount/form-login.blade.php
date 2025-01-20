{{-- *
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
  --}}
{{--  Exit if accessed directly. --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


if ( ! defined( 'ABSPATH' ) ) {
	exit; }

do_action( 'woocommerce_before_customer_login_form' ); @endphp


<div class="u-columns col2-set lg:grid lg:grid-cols-12 lg:auto-rows-min lg:gap-x-14 lg:min-h-full" id="customer_login">
	<div class="u-column1 col-1 lg:col-start-1 lg:col-span-6">

		<h2 class="mt-6 text-2xl font-extrabold text-gray-900">@php esc_html_e( 'Login', 'woocommerce' ); @endphp</h2>

		<form class="woocommerce-form woocommerce-form-login login space-y-6 mt-6" method="post">
			@php do_action( 'woocommerce_login_form_start' ); @endphp
			<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Username or email address', 'woocommerce' ); @endphp&nbsp;<span class="required">*</span></label>
				<div class="mt-1">
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="username" id="username" autocomplete="username" value="{!! ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '' !!}" />{{--  @codingStandardsIgnoreLine  --}}
				</div>
			</div>
			<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide space-y-1">
				<label for="password" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Password', 'woocommerce' ); @endphp&nbsp;<span class="required">*</span></label>
				<div class="mt-1">
					<input class="woocommerce-Input woocommerce-Input--text input-text border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" type="password" name="password" id="password" autocomplete="current-password" />
				</div>
			</div>

			@php do_action( 'woocommerce_login_form' ); @endphp

			<div class="form-row">
				<div class="flex items-center justify-between">
					<div class="flex items-center">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme ml-2 block text-sm text-gray-900">
							<span>@php esc_html_e( 'Remember me', 'woocommerce' ); @endphp</span>
						</label>
					</div>
					<div class="woocommerce-LostPassword lost_password text-sm">
						<a href="{!! esc_url( wp_lostpassword_url() ) !!}" class="font-medium text-indigo-600 hover:text-indigo-500">@php esc_html_e( 'Lost your password?', 'woocommerce' ); @endphp</a>
					</div>
				</div>
			</div>

			<div>
				@php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); @endphp
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" name="login" value="@php esc_attr_e( 'Log in', 'woocommerce' ); @endphp">@php esc_html_e( 'Log in', 'woocommerce' ); @endphp</button>
			</div>
			@php do_action( 'woocommerce_login_form_end' ); @endphp
		</form>
	</div>

	@if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) )
		<div class="mt-6 relative lg:hidden">
			<div class="absolute inset-0 flex items-center" aria-hidden="true">
				<div class="w-full border-t border-gray-300"></div>
			</div>
			<div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">
					{{ __('Or', 'apiary') }}
                </span>
			</div>
		</div>
		<div class="u-column2 col-2 lg:col-start-7 lg:col-span-6">
			<h2 class="mt-6 text-2xl font-extrabold text-gray-900">@php esc_html_e( 'Register', 'woocommerce' ); @endphp</h2>
			<form method="post" class="woocommerce-form woocommerce-form-register register space-y-6 mt-6" @php do_action( 'woocommerce_register_form_tag' ); @endphp >
				@php do_action( 'woocommerce_register_form_start' ); @endphp
				@if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) )
					<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_username" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Username', 'woocommerce' ); @endphp&nbsp;<span class="required">*</span></label>
						<div class="mt-1">
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="username" id="reg_username" autocomplete="username" value="{!! ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '' !!}" />{{--  @codingStandardsIgnoreLine  --}}
						</div>
					</div>
				@endif

				<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_email" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Email address', 'woocommerce' ); @endphp&nbsp;<span class="required">*</span></label>
					<div class="mt-1">
						<input type="email" class="woocommerce-Input woocommerce-Input--text input-text shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="email" id="reg_email" autocomplete="email" value="{!! ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : '' !!}" />{{--  @codingStandardsIgnoreLine  --}}
					</div>
					@if ( 'no' !== get_option( 'woocommerce_registration_generate_password' ) )
						<p class="mt-2 text-sm text-gray-500">@php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); @endphp</p>
					@endif
				</div>

				@if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) )
					<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_password" class="text-sm font-medium text-gray-700">@php esc_html_e( 'Password', 'woocommerce' ); @endphp&nbsp;<span class="required">*</span></label>
						<div class="mt-1">
							<input type="password" class="woocommerce-Input woocommerce-Input--text input-text shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" name="password" id="reg_password" autocomplete="new-password" />
						</div>
					</div>
				@endif

				@php do_action( 'woocommerce_register_form' ); @endphp

				<div class="woocommerce-form-row form-row">
					@php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); @endphp
					<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" name="register" value="@php esc_attr_e( 'Register', 'woocommerce' ); @endphp">@php esc_html_e( 'Register', 'woocommerce' ); @endphp</button>
				</div>

				@php do_action( 'woocommerce_register_form_end' ); @endphp

			</form>

		</div>
	@endif
</div>


@php do_action( 'woocommerce_after_customer_login_form' ); @endphp
