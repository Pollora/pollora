<header class="relative bg-white border-b border-gray-200 text-sm font-medium text-gray-700">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="relative flex justify-end sm:justify-center">
            <a href="{{ home_url() }}" class="absolute left-0 top-1/2 -mt-4">
                <span class="sr-only">{{ bloginfo('site_title') }}</span>
                {!! \App\Themes\Apiary\custom_logo('h-8 w-auto', 'custom-logo-link', false) !!}
            </a>
            <nav aria-label="Progress" class="hidden sm:block">
                <ol role="list" class="flex space-x-4">
                    <li class="flex items-center">
                        @if (!is_thank_you_page() && !is_cart())
                            <a href="{{ wc_get_cart_url() }}">{{ __('Cart', 'woocommerce') }}</a>
                        @else
                            <span @if (is_cart())aria-current="page" class="text-indigo-600"@endif>{{ __('Cart', 'woocommerce') }}</span>
                        @endif
                        <svg class="w-5 h-5 text-gray-300 ml-4" aria-hidden="true" x-description="Heroicon name: solid/chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="flex items-center">
                        @if (!is_thank_you_page() && !is_checkout_form())
                            <a href="{{ wc_get_checkout_url() }}">{{ __('Order', 'woocommerce') }}</a>
                        @else
                            <span @if (is_checkout_form())aria-current="page" class="text-indigo-600"@endif>{{ __('Order', 'woocommerce') }}</span>
                        @endif
                        <svg class="w-5 h-5 text-gray-300 ml-4" aria-hidden="true" x-description="Heroicon name: solid/chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </li>
                    <li class="flex items-center">
                        <span @if (is_thank_you_page())aria-current="page" class="text-indigo-600"@endif>{{ __('Confirmation', 'woocommerce') }}</span>
                    </li>
                </ol>
            </nav>
            <p class="sm:hidden">Step 2 of 4</p>
        </div>
    </div>
</header>
