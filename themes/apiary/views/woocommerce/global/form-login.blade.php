{{-- *
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
  --}}
{{--  Exit if accessed directly. --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
    if ( is_user_logged_in() ) {
        return;
    }
@endphp

<div x-show="loginModalOpen" class="first-hidden-state fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title"
     x-ref="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">

        <div x-show="loginModalOpen"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             @click="loginModalOpen = false"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-description="Background overlay, show/hide based on modal state."
             aria-hidden="true">
        </div>

        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&ZeroWidthSpace;</span>

        <div x-show="loginModalOpen"
             class="bg-white rounded-md inline-block transform overflow-hidden px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6 sm:align-middle"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-description="Modal panel, show/hide based on modal state."
        ">
        <form class="woocommerce-form woocommerce-form-login login space-y-6" method="POST">

            @php do_action( 'woocommerce_login_form_start' ); @endphp

            <div class="text-sm text-gray-500">
                {!! ( $message ) ? wpautop( wptexturize( $message ) ) : '' !!}
            </div>

            <div class="form-row form-row-first">
                <label for="username" class="text-brown-500 block text-sm font-medium italic">
                    {{ __( 'Username or email', 'woocommerce' ) }}&nbsp;<span class="required">*</span>
                </label>
                <div class="mt-1">
                    <input type="text"
                           class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-xs focus:border-indigo-500 focus:outline-hidden focus:ring-indigo-500 sm:text-sm"
                           name="username" id="username" autocomplete="username"/>
                </div>
            </div>
            <div class="form-row form-row-last">
                <label for="password" class="text-brown-500 block text-sm font-medium italic">
                    {{ __( 'Password', 'woocommerce' ) }}&nbsp;<span class="required">*</span>
                </label>
                <div class="mt-1">
                    <input class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-xs focus:border-indigo-500 focus:outline-hidden focus:ring-indigo-500 sm:text-sm"
                           type="password" name="password" id="password" autocomplete="current-password"/>
                </div>
            </div>

            @php do_action( 'woocommerce_login_form' ); @endphp

            <div class="form-row">
                <div class="-mt-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="rememberme"
                               class="woocommerce-form__input woocommerce-form__input-checkbox h-4 w-4 rounded-sm border-gray-300 text-green-600 focus:ring-indigo-500"
                               name="rememberme" type="checkbox" id="rememberme" value="forever"/>
                        <label for="rememberme"
                               class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme ml-2 block text-sm">
                            <span>{{ __( 'Remember me', 'woocommerce' ) }}</span>
                        </label>
                    </div>
                    <div class="lost_password">
                        <a href="{!! esc_url( wp_lostpassword_url() ) !!}"
                           class="font-medium text-sm text-indigo-500 hover:text-indigo-600">
                            {{ __( 'Lost your password?', 'woocommerce' ) }}
                        </a>
                    </div>
                </div>
            </div>
            <div>
                @php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); @endphp
                <input type="hidden" name="redirect" value="{!! esc_url( $redirect ) !!}"/>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-4">
                    <button type="submit" name="login"
                            class="woocommerce-button button woocommerce-form-login__submit inline-flex w-full justify-center border border-transparent bg-indigo-500 rounded-md px-4 py-2 text-sm font-medium text-white shadow-xs hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-2">
                        {{ __( 'Login', 'woocommerce' ) }}
                    </button>
                    <button type="button" value="@php esc_attr_e( 'Login', 'woocommerce' ); @endphp"
                            class="border-brown-500 bg-beige-500 text-brown-500 hover:bg-beige-300 mt-3 inline-flex w-full justify-center border px-4 py-2 rounded-md text-sm font-medium shadow-xs focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0"
                            @click="loginModalOpen = false">
                        {{ __( 'Cancel', 'woocommerce' ) }}
                    </button>
                </div>
            </div>

            @php do_action( 'woocommerce_login_form_end' ); @endphp

        </form>
    </div>

</div>
</div>