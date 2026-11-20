{{--
 * Login Form
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 --}}
@php

$registrationEnabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );

do_action( 'woocommerce_before_customer_login_form' );
@endphp

@if ($registrationEnabled)
{{-- ═══ LAYOUT: Login + Register (2 colonnes desktop, empilées mobile) ═══ --}}
<div class="lg:grid lg:grid-cols-2 lg:gap-x-8 xl:gap-x-12" id="customer_login">

	{{-- Login --}}
	<div class="rounded-xl border border-outline bg-white p-6 sm:p-8">
		<h2 class="text-xl font-bold text-foreground">@php esc_html_e( 'Login', 'woocommerce' ); @endphp</h2>
		<p class="mt-1 text-sm text-muted">{{ __('Already have an account? Sign in below.', 'apiary') }}</p>

		<form class="woocommerce-form woocommerce-form-login login mt-6 space-y-5" method="post">
			@php do_action( 'woocommerce_login_form_start' ); @endphp

			<div>
				<label for="username" class="block text-sm font-medium text-foreground">@php esc_html_e( 'Username or email address', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span></label>
				<input type="text" class="mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden" name="username" id="username" autocomplete="username" required aria-required="true" value="{!! ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '' !!}" />
			</div>

			<div>
				<label for="password" class="block text-sm font-medium text-foreground">@php esc_html_e( 'Password', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span></label>
				<input class="mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
			</div>

			@php do_action( 'woocommerce_login_form' ); @endphp

			<div class="flex items-center justify-between">
				<label class="flex items-center gap-2 text-sm text-muted cursor-pointer">
					<input class="h-4 w-4 rounded border-outline text-primary focus:ring-ring" name="rememberme" type="checkbox" id="rememberme" value="forever" />
					@php esc_html_e( 'Remember me', 'woocommerce' ); @endphp
				</label>
				<a href="{!! esc_url( wp_lostpassword_url() ) !!}" class="text-sm font-medium text-primary hover:text-primary-hover">@php esc_html_e( 'Lost your password?', 'woocommerce' ); @endphp</a>
			</div>

			<div>
				@php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); @endphp
				<button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors" name="login" value="@php esc_attr_e( 'Log in', 'woocommerce' ); @endphp">@php esc_html_e( 'Log in', 'woocommerce' ); @endphp</button>
			</div>

			@php do_action( 'woocommerce_login_form_end' ); @endphp
		</form>
	</div>

	{{-- Separator mobile --}}
	<div class="relative my-8 lg:hidden">
		<div class="absolute inset-0 flex items-center"><div class="w-full border-t border-outline"></div></div>
		<div class="relative flex justify-center text-sm"><span class="bg-white px-4 text-muted">{{ __('Or', 'apiary') }}</span></div>
	</div>

	{{-- Register --}}
	<div class="rounded-xl border border-outline bg-surface p-6 sm:p-8">
		<h2 class="text-xl font-bold text-foreground">@php esc_html_e( 'Register', 'woocommerce' ); @endphp</h2>
		<p class="mt-1 text-sm text-muted">{{ __('Create an account to track orders and save your details.', 'apiary') }}</p>

		<form method="post" class="woocommerce-form woocommerce-form-register register mt-6 space-y-5" @php do_action( 'woocommerce_register_form_tag' ); @endphp>
			@php do_action( 'woocommerce_register_form_start' ); @endphp

			@if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) )
				<div>
					<label for="reg_username" class="block text-sm font-medium text-foreground">@php esc_html_e( 'Username', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span></label>
					<input type="text" class="mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden" name="username" id="reg_username" autocomplete="username" value="{!! ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '' !!}" />
				</div>
			@endif

			<div>
				<label for="reg_email" class="block text-sm font-medium text-foreground">@php esc_html_e( 'Email address', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span></label>
				<input type="email" class="mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden" name="email" id="reg_email" autocomplete="email" value="{!! ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : '' !!}" />
				@if ( 'no' !== get_option( 'woocommerce_registration_generate_password' ) )
					<p class="mt-2 text-xs text-muted">@php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); @endphp</p>
				@endif
			</div>

			@if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) )
				<div>
					<label for="reg_password" class="block text-sm font-medium text-foreground">@php esc_html_e( 'Password', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span></label>
					<input type="password" class="mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden" name="password" id="reg_password" autocomplete="new-password" />
				</div>
			@endif

			@php do_action( 'woocommerce_register_form' ); @endphp

			<div>
				@php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); @endphp
				<button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors" name="register" value="@php esc_attr_e( 'Register', 'woocommerce' ); @endphp">@php esc_html_e( 'Register', 'woocommerce' ); @endphp</button>
			</div>

			@php do_action( 'woocommerce_register_form_end' ); @endphp
		</form>
	</div>
</div>

@else
{{-- ═══ LAYOUT: Login seul (centré, max-w-sm) ═══ --}}
<div class="max-w-sm mx-auto" id="customer_login">
	<div class="text-center mb-8">
		<h2 class="text-2xl font-bold text-foreground">@php esc_html_e( 'Sign in to your account', 'woocommerce' ); @endphp</h2>
	</div>

	<div class="rounded-xl border border-outline bg-white p-6 sm:p-8">
		<form class="woocommerce-form woocommerce-form-login login space-y-5" method="post">
			@php do_action( 'woocommerce_login_form_start' ); @endphp

			<div>
				<label for="username" class="block text-sm font-medium text-foreground">@php esc_html_e( 'Username or email address', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span></label>
				<input type="text" class="mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden" name="username" id="username" autocomplete="username" required aria-required="true" value="{!! ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '' !!}" />
			</div>

			<div>
				<label for="password" class="block text-sm font-medium text-foreground">@php esc_html_e( 'Password', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span></label>
				<input class="mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
			</div>

			@php do_action( 'woocommerce_login_form' ); @endphp

			<div class="flex items-center justify-between">
				<label class="flex items-center gap-2 text-sm text-muted cursor-pointer">
					<input class="h-4 w-4 rounded border-outline text-primary focus:ring-ring" name="rememberme" type="checkbox" id="rememberme" value="forever" />
					@php esc_html_e( 'Remember me', 'woocommerce' ); @endphp
				</label>
				<a href="{!! esc_url( wp_lostpassword_url() ) !!}" class="text-sm font-medium text-primary hover:text-primary-hover">@php esc_html_e( 'Lost your password?', 'woocommerce' ); @endphp</a>
			</div>

			<div>
				@php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); @endphp
				<button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors" name="login" value="@php esc_attr_e( 'Log in', 'woocommerce' ); @endphp">@php esc_html_e( 'Log in', 'woocommerce' ); @endphp</button>
			</div>

			@php do_action( 'woocommerce_login_form_end' ); @endphp
		</form>
	</div>
</div>
@endif

@php do_action( 'woocommerce_after_customer_login_form' ); @endphp
