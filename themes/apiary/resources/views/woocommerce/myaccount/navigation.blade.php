{{--
 * My Account navigation
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 --}}

@php
    $menu_items = wc_get_account_menu_items();
    $icons = config('theme.woocommerce.myaccount.icons', []);
    $default_icon = $icons['default'] ?? null;
@endphp

<div class="account-wrapper lg:flex lg:gap-8 mt-6">
    {{-- Desktop sidebar --}}
    <aside class="hidden lg:block lg:w-60 lg:shrink-0">
        @php do_action( 'woocommerce_before_account_navigation' ); @endphp
        <nav class="woocommerce-MyAccount-navigation sticky top-24" aria-label="@php esc_attr_e( 'Account pages', 'woocommerce' ); @endphp">
            <ul class="space-y-1 list-none pl-0">
                @foreach ( $menu_items as $endpoint => $label )
                    @php
                        $item_class = wc_get_account_menu_item_classes( $endpoint );
                        $current = str_contains($item_class, 'is-active');
                        $icon = $icons[$endpoint] ?? $default_icon;
                    @endphp
                    <li class="{{ $item_class }}">
                        <a href="{{ esc_url( wc_get_account_endpoint_url( $endpoint ) ) }}"
                           {!! $current ? 'aria-current="page"' : '' !!}
                           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                                  {{ $current
                                      ? 'bg-primary text-white'
                                      : 'text-muted hover:bg-surface-alt hover:text-foreground' }}">
                            @if ($icon)
                                <span class="{{ $current ? 'text-white' : 'text-subtle group-hover:text-muted' }} transition-colors shrink-0">
                                    {!! $icon !!}
                                </span>
                            @endif
                            {!! esc_html( $label ) !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
        @php do_action( 'woocommerce_after_account_navigation' ); @endphp
    </aside>

    {{-- Mobile horizontal tabs --}}
    <div class="lg:hidden mb-6">
        @php do_action( 'woocommerce_before_account_navigation' ); @endphp
        <nav class="woocommerce-MyAccount-navigation -mx-4 px-4 overflow-x-auto scrollbar-hide" aria-label="@php esc_attr_e( 'Account pages', 'woocommerce' ); @endphp">
            <ul class="flex gap-1.5 list-none pl-0 min-w-max pb-2">
                @foreach ( $menu_items as $endpoint => $label )
                    @php
                        $item_class = wc_get_account_menu_item_classes( $endpoint );
                        $current = str_contains($item_class, 'is-active');
                        $icon = $icons[$endpoint] ?? $default_icon;
                    @endphp
                    <li class="{{ $item_class }}">
                        <a href="{{ esc_url( wc_get_account_endpoint_url( $endpoint ) ) }}"
                           {!! $current ? 'aria-current="page"' : '' !!}
                           class="flex items-center gap-2 whitespace-nowrap rounded-full px-4 py-2 text-sm font-medium transition-colors
                                  {{ $current
                                      ? 'bg-primary text-white shadow-xs'
                                      : 'bg-surface-alt text-muted hover:bg-outline hover:text-foreground' }}">
                            @if ($icon)
                                <span class="{{ $current ? 'text-white' : 'text-subtle' }} shrink-0">
                                    {!! $icon !!}
                                </span>
                            @endif
                            {!! esc_html( $label ) !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
        @php do_action( 'woocommerce_after_account_navigation' ); @endphp
    </div>
