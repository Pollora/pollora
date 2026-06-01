{{--
 * Checkout header with progress steps
 *
 * @package Theme\Apiary\WooCommerce
 --}}
@php
    $is_cart = is_cart();
    $is_checkout = is_checkout_form();
    $is_thankyou = is_thank_you_page();

    $steps = [
        'cart'     => ['label' => __('Cart', 'woocommerce'),         'active' => $is_cart,     'completed' => $is_checkout || $is_thankyou],
        'checkout' => ['label' => __('Checkout', 'woocommerce'),     'active' => $is_checkout, 'completed' => $is_thankyou],
        'confirm'  => ['label' => __('Confirmation', 'woocommerce'), 'active' => $is_thankyou, 'completed' => false],
    ];

    $current_step = $is_thankyou ? 3 : ($is_checkout ? 2 : 1);
@endphp

<header class="bg-white border-b border-outline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">
            {{-- Logo --}}
            <a href="{{ home_url() }}" class="shrink-0 text-foreground no-underline">
                @if (has_custom_logo())
                    {!! \Theme\Apiary\custom_logo('h-7 sm:h-8 w-auto', 'custom-logo-link', false) !!}
                @else
                    <span class="text-lg sm:text-xl font-bold tracking-tight">{{ get_bloginfo('name') }}</span>
                @endif
            </a>

            {{-- Desktop step indicator --}}
            <nav aria-label="Progress" class="hidden sm:block">
                <ol role="list" class="flex items-center gap-2">
                    @foreach ($steps as $key => $step)
                        <li class="flex items-center">
                            @if ($step['completed'])
                                {{-- Completed step --}}
                                <a href="{{ $key === 'cart' ? wc_get_cart_url() : ($key === 'checkout' ? wc_get_checkout_url() : '#') }}"
                                   class="flex items-center gap-2 text-sm font-medium text-primary">
                                    <span class="flex size-6 items-center justify-center rounded-full bg-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-3.5 text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                    </span>
                                    {{ $step['label'] }}
                                </a>
                            @elseif ($step['active'])
                                {{-- Current step --}}
                                <span class="flex items-center gap-2 text-sm font-semibold text-foreground" aria-current="step">
                                    <span class="flex size-6 items-center justify-center rounded-full border-2 border-primary bg-white text-xs font-bold text-primary">
                                        {{ $loop->iteration }}
                                    </span>
                                    {{ $step['label'] }}
                                </span>
                            @else
                                {{-- Upcoming step --}}
                                <span class="flex items-center gap-2 text-sm font-medium text-subtle">
                                    <span class="flex size-6 items-center justify-center rounded-full border-2 border-outline bg-white text-xs font-medium text-subtle">
                                        {{ $loop->iteration }}
                                    </span>
                                    {{ $step['label'] }}
                                </span>
                            @endif

                            @if (!$loop->last)
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-2 size-4 text-outline" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>

            {{-- Mobile step indicator --}}
            <div class="sm:hidden">
                <span class="text-xs font-medium text-muted">
                    {{ sprintf( __('Step %1$d of %2$d', 'apiary'), $current_step, count($steps) ) }}
                </span>
            </div>

            {{-- Secure badge --}}
            <div class="hidden sm:flex items-center gap-1.5 text-xs font-medium text-muted">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-success">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                {{ __('Secure checkout', 'apiary') }}
            </div>
        </div>

        {{-- Mobile progress bar --}}
        <div class="sm:hidden pb-3">
            <div class="flex items-center gap-1.5">
                @foreach ($steps as $key => $step)
                    <div class="flex-1 h-1 rounded-full {{ $step['completed'] || $step['active'] ? 'bg-primary' : 'bg-outline' }}"></div>
                @endforeach
            </div>
        </div>
    </div>
</header>
