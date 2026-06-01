{{--
 * Home page template (blog index)
 *
 * @package Theme\Apiary
 --}}
@extends('layouts.app')

@section('content')
    {{-- Featured products --}}
    @if (class_exists('WooCommerce'))
        <section class="mt-12">
            <h2 class="text-2xl font-bold tracking-tight text-foreground">{{ __('New arrivals', 'woocommerce') }}</h2>
            <p class="mt-2 text-sm text-muted">{{ __('Check out the latest additions to our collection.', 'apiary') }}</p>
            <div class="mt-8">
                {!! do_shortcode('[products limit="8" columns="4" orderby="date" order="DESC"]') !!}
            </div>
        </section>

        {{-- Product categories --}}
        @php
            $categories = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => 0,
                'exclude' => [get_option('default_product_cat')],
                'number' => 6,
            ]);
        @endphp
        @if (!is_wp_error($categories) && !empty($categories))
            <section class="mt-20">
                <h2 class="text-2xl font-bold tracking-tight text-foreground">{{ __('Shop by category', 'apiary') }}</h2>
                <div class="mt-8 grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-3">
                    @foreach ($categories as $category)
                        @php $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true); @endphp
                        <a href="{{ get_term_link($category) }}" class="group relative block overflow-hidden rounded-lg bg-surface">
                            @if ($thumbnail_id)
                                {!! wp_get_attachment_image($thumbnail_id, 'medium_large', false, [
                                    'class' => 'w-full h-48 object-cover object-center group-hover:scale-105 transition-transform duration-300',
                                ]) !!}
                            @else
                                <div class="w-full h-48 bg-surface-alt flex items-center justify-center">
                                    <svg class="w-12 h-12 text-subtle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-foreground group-hover:text-primary transition-colors">{{ $category->name }}</h3>
                                <p class="mt-1 text-xs text-muted">{{ sprintf(_n('%d product', '%d products', $category->count, 'woocommerce'), $category->count) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- On sale --}}
        <section class="mt-20 mb-12">
            <h2 class="text-2xl font-bold tracking-tight text-foreground">{{ __('On sale', 'woocommerce') }}</h2>
            <p class="mt-2 text-sm text-muted">{{ __('Great deals on selected items.', 'apiary') }}</p>
            <div class="mt-8">
                {!! do_shortcode('[sale_products limit="4" columns="4" orderby="rand"]') !!}
            </div>
        </section>
    @else
        {{-- Blog fallback when WooCommerce is not active --}}
        <div class="mt-16">
            @hasposts
                <div class="grid max-w-xl grid-cols-1 gap-y-16 lg:max-w-none lg:grid-cols-3 lg:gap-x-8">
                    @posts
                        <article class="group relative flex flex-col items-start">
                            <time class="text-sm text-muted" datetime="@published('c')">@published</time>
                            <h2 class="mt-2 text-lg font-semibold text-foreground group-hover:text-primary transition-colors">
                                <a href="@permalink">@title</a>
                            </h2>
                            <p class="mt-2 text-sm text-muted line-clamp-3">@excerpt</p>
                        </article>
                    @endposts
                </div>
            @endhasposts
        </div>
    @endif
@endsection
