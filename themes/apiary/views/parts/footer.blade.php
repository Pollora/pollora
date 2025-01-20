<footer aria-labelledby="footer-heading" class="bg-white">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (
            has_nav_menu('menu_account')
            || has_nav_menu('menu_service')
            || has_nav_menu('menu_company')
            || has_nav_menu('menu_social')
        )
            <div class="border-t border-gray-200 py-20 grid grid-cols-2 gap-8 sm:gap-y-0 sm:grid-cols-2 lg:grid-cols-4">
                @foreach(['menu_account', 'menu_service', 'menu_company', 'menu_social'] as $footer_menu)
                    @if ($menus->$footer_menu->itemCount > 0)
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">
                                {{ $menus->$footer_menu->datas->name }}
                            </h3>
                            <ul role="list" class="mt-6 space-y-6">
                                @foreach ($menus->$footer_menu->items as $item)
                                    <li class="text-sm">
                                        <a href="{{ $item->url }}" id="menu-{{ $item->objectId }}"
                                           class="{{ $item->classes ?? '' }} text-gray-500 hover:text-gray-600{{ $item->active ? ' active' : '' }}">
                                            {{ $item->label }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="border-t border-gray-100 py-10 sm:flex sm:items-center">
            <p class="w-full mt-6 text-sm text-gray-500 text-center md:text-right sm:mt-0">&copy; {{ date('Y') }} {{ bloginfo('site_title') }}</p>
        </div>
    </div>
</footer>