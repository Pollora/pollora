{{--
 * Checkout page layout
 *
 * @package Theme\Apiary\WooCommerce
 --}}
@extends(config('theme.woocommerce.checkout.simplified_layout', false) ? 'woocommerce.layouts.checkout' : 'layouts.app')

@section('content')
    @posts
        <article id="post-{{ get_the_ID() }}" {!! post_class() !!}>
            @content
        </article>
    @endposts
@endsection
