{{--
 * Review author avatar
 *
 * @package Theme\Apiary\WooCommerce
 --}}
<div class="flex-none">
    {!! get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '80' ), '', '', [ 'class' => 'w-10 h-10 bg-surface-alt rounded-full ring-2 ring-outline' ] ) !!}
</div>
