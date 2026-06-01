{{--
 * Default page template
 *
 * @package Theme\Apiary
 --}}
@php
    $post_id = (int) get_the_ID();
    $meta = get_post_meta($post_id, '_hide_page_title', true);

    $use_checkout_layout = config('theme.woocommerce.checkout.simplified_layout', false)
        && function_exists('is_woocommerce')
        && (is_cart() || is_checkout());

    $show_title = ($meta !== '')
        ? apply_filters('theme/page/show_title', ! $meta, $post_id)
        : apply_filters('theme/page/default_show_title', true, $post_id);
@endphp

@extends($use_checkout_layout ? 'woocommerce.layouts.checkout' : 'layouts.app')

@section('content')
<!-- data-pollora-template="page" -->
    @if ($use_checkout_layout)
        <div class="bg-white rounded-xl border border-outline p-5 sm:p-8">
            @if ($show_title)
                <h1 class="text-2xl font-bold tracking-tight text-foreground mb-6">@title</h1>
            @endif
            @content
        </div>
    @else
        <div class="mt-9">
            @if ($show_title)
                <h1 class="text-4xl font-bold tracking-tight text-foreground sm:text-5xl">@title</h1>
            @endif
            <div class="{{ $show_title ? 'mt-6' : '' }} prose max-w-none">
                @content
            </div>
        </div>
    @endif
@endsection
