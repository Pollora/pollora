@php
use \App\Themes\Apiary\Walkers\MenuPrimary;
@endphp
<div x-data="{ open: false }" @keydown.window.escape="open = false" class="bg-white">
    <header class="relative z-10">
        <div x-show="open === 'menu'" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-description="Off-canvas menu overlay, show/hide based on off-canvas menu state." class="fixed inset-0 bg-black bg-opacity-25 z-20" @click="open = false" aria-hidden="true" style="display: none;">
        </div>
        <div aria-label="Top">
            <!-- Top navigation -->
            @if (is_store_notice_showing())
                <div class="bg-gray-900">
                    <div class="max-w-7xl mx-auto h-10 px-4 flex items-center justify-between sm:px-6 lg:px-8">
                        <p class="flex-1 text-center text-sm font-medium text-white lg:flex-none w-full">
                            {!! get_option('woocommerce_demo_store_notice') !!}
                        </p>
                    </div>
                </div>
            @endif

            <div class="bg-white">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="border-b border-gray-200 pl-16 md:pl-0">
                        <div class="h-16 flex items-center justify-between">
                            <div class="w-full text-gray-700 bg-white">
                                <div class="flex flex-col max-w-screen-xl px-4 mx-auto md:items-center md:justify-between md:flex-row bg-white">
                                    <div class="flex flex-row items-center justify-between">
                                        <a href="{{ home_url() }}" class="text-lg font-semibold tracking-widest text-gray-900 uppercase rounded-lg focus:outline-none focus:shadow-outline">
                                            <span class="sr-oly">{{ bloginfo('site_title') }}</span>
                                            {!! \App\Themes\Apiary\custom_logo('h-8 w-auto', 'custom-logo-link', false) !!}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Mobile menu and search (lg-) -->
                            <div class="flex-1 flex items-center lg:hidden">
                                <!-- Search -->
                                <a href="#" class="ml-2 p-2 text-gray-400 hover:text-gray-500">
                                    <span class="sr-only">Search</span>
                                    <svg class="w-6 h-6" x-description="Heroicon name: outline/search" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </a>
                            </div>

                            <!-- Logo (lg-) -->
                            <span data-href="{{ str_rot13(get_home_url()) }}" class="lg:hidden">
                                <span class="sr-only">{{ bloginfo('site_title') }}</span>
                                {!! \App\Themes\Apiary\custom_logo('h-8 w-auto', 'custom-logo-link', false) !!}
                            </span>

                            <div class="flex-1 flex items-center justify-end">
                                <div class="flex items-center lg:ml-8">
                                    <div class="flex space-x-8">
                                        <div class="hidden lg:flex">
                                            <a href="#" class="-m-2 p-2 text-gray-400 hover:text-gray-500">
                                                <span class="sr-only">{{ __('Search', 'woocommerce') }}</span>
                                                <svg class="w-6 h-6" x-description="Heroicon name: outline/search" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </a>
                                        </div>

                                        <div class="flex">
                                            <a href="#" class="-m-2 p-2 text-gray-400 hover:text-gray-500">
                                                <span class="sr-only">
                                                    @if (is_user_logged_in())
                                                        {{ __('My Account','woocommerce') }}
                                                    @else
                                                        {{ __('Login / Register','woocommerce') }}
                                                    @endif
                                                </span>
                                                <svg class="w-6 h-6" x-description="Heroicon name: outline/user" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>

                                    <span class="mx-4 h-6 w-px bg-gray-200 lg:mx-6" aria-hidden="true"></span>

                                    <div class="flow-root">
                                        <button id="cart-btn" @click="open = 'cart'" class="group -m-2 p-2 flex items-center">
                                            <svg class="flex-shink-0 h-6 w-6 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: outline/shopping-cart" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800"><spa class="cart-count">{{ wc_get_cart_item_count() }}</span></span>
                                            <span class="sr-only">{{ __('items in cart, view bag', 'apiary') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <nav x-show="open === 'menu'"
         x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="max-w-7xl md:mx-auto md:px-6 lg:px-8 md:!flex flex-col flex-grow pb-4 md:pb-0 md:flex md:justify-end md:flex-row absolute w-[calc(100vw-65px)] top-0 h-full pt-[65px] md:relative md:w-auto md:h-auto md:pt-0 z-50 bg-white">
        <div class="flex flex-col flex-grow md:flex-row w-full md:border-b md:border-gray-200 py-2 -mx-4">
            {!! wp_nav_menu([
                'walker' => new MenuPrimary(),
                'theme_location' => 'menu-primary',
                'container' => false,
                'items_wrap' => '%3$s',
                'link_config' => [
                    [
                        'depth' => 0,
                        'class' => 'px-4 py-2 mt-2 text-sm font-semibold text-gray-900 bg-transparent rounded-lg md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline'
                    ],
                    [
                        'depth' => 1,
                        'class' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white'
                    ]
                ],
                'item_config' => [
                    [
                        'depth' => 0,
                        'class' => 'inline-block'
                    ],
                ],
            ]) !!}
        </div>
    </nav>
    <button class="fixed z-50 left-0 top-0 md:hidden focus:outline-none focus:shadow-outline h-16 w-16 bg-white" @click="open = open === 'menu' ? false : 'menu'">
        <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6 m-auto">
            <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            <path x-show="open === 'menu'" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex z-40">
        <div x-show="open === 'cart'" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md" x-description="Slide-over panel, show/hide based on slide-over state.">
            <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
            </div>
        </div>
    </div>
</div>
