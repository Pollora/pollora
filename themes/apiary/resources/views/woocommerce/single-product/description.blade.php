{{--
 * Product description tab content
 *
 * @package Theme\Apiary\WooCommerce
 --}}
<div class="product_description mt-10 text-muted">
    <h2 class="text-sm font-medium text-foreground">{{ __('Description', 'apiary') }}</h2>
    <div class="mt-4 prose prose-sm text-muted">
        @php the_content(); @endphp
    </div>
</div>
