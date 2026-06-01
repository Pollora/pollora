{{--
 * Site footer
 *
 * @package Theme\Apiary
 --}}
<footer aria-labelledby="footer-heading" class="bg-white">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $footerLocations = ['menu-account', 'menu-service', 'menu-company', 'menu-social'];
            $hasFooterMenus = false;
            foreach ($footerLocations as $loc) {
                if (has_nav_menu($loc)) { $hasFooterMenus = true; break; }
            }
        @endphp

        @if ($hasFooterMenus)
            <div class="border-t border-outline py-16 grid grid-cols-2 gap-8 sm:gap-y-0 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($footerLocations as $location)
                    @if (has_nav_menu($location))
                        @php
                            $locations = get_nav_menu_locations();
                            $menuObj = wp_get_nav_menu_object($locations[$location] ?? 0);
                        @endphp
                        @if ($menuObj)
                            <div>
                                <h3 class="text-sm font-medium text-foreground">{{ $menuObj->name }}</h3>
                                <ul role="list" class="mt-6 space-y-4 list-none pl-0">
                                    @foreach (wp_get_nav_menu_items($menuObj->term_id) ?: [] as $item)
                                        <li class="text-sm">
                                            <a href="{{ $item->url }}" class="text-muted hover:text-foreground transition-colors">
                                                {{ $item->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        @else
            {{-- Fallback footer content when no menus are configured --}}
            <div class="border-t border-outline py-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @if (class_exists('WooCommerce'))
                    <div>
                        <h3 class="text-sm font-medium text-foreground">{{ __('Shop', 'woocommerce') }}</h3>
                        <ul role="list" class="mt-6 space-y-4 list-none pl-0">
                            <li><a href="{{ wc_get_page_permalink('shop') }}" class="text-sm text-muted hover:text-foreground transition-colors">{{ __('All products', 'woocommerce') }}</a></li>
                            <li><a href="{{ wc_get_page_permalink('cart') }}" class="text-sm text-muted hover:text-foreground transition-colors">{{ __('Cart', 'woocommerce') }}</a></li>
                            <li><a href="{{ wc_get_page_permalink('myaccount') }}" class="text-sm text-muted hover:text-foreground transition-colors">{{ __('My account', 'woocommerce') }}</a></li>
                        </ul>
                    </div>
                @endif
                <div>
                    <h3 class="text-sm font-medium text-foreground">{{ __('About', 'apiary') }}</h3>
                    <p class="mt-6 text-sm text-muted leading-relaxed">
                        {{ get_bloginfo('description') }}
                    </p>
                </div>
            </div>
        @endif

        <div class="border-t border-outline py-10 sm:flex sm:items-center sm:justify-between">
            <p class="text-sm text-muted">&copy; {{ date('Y') }} {{ bloginfo('site_title') }}</p>
            <div class="mt-4 flex space-x-6 sm:mt-0">
                @php do_action('apiary_footer_links') @endphp
            </div>
        </div>
    </div>
</footer>
