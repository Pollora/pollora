{{-- *
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
    defined( 'ABSPATH' ) || exit;

    global $product;

    if ( ! comments_open() ) {
        return;
    }
@endphp
<div id="reviews" class="woocommerce-Reviews">
    <div id="comments">
        <h2 class="sr-only woocommerce-Reviews-title">
        {{--  translators: 1: reviews count 2: product name  --}}
        {{--  WPCS: XSS ok. --}}
        @php
            $count = $product->get_review_count();
            if ( $count && wc_review_ratings_enabled() ) {
                $reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
                echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product );             } else {
                esc_html_e( 'Reviews', 'woocommerce' );
            }
            @endphp
        </h2>

        
        @if ( have_comments() )
            <ol class="commentlist">
                @php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); @endphp
            </ol>
            @if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) )
                <nav class="woocommerce-pagination">
                    {!!
                        paginate_comments_links(
                            apply_filters(
                                'woocommerce_comment_pagination_args',
                                array(
                                    'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                                    'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                                    'type'      => 'list',
                                )
                            )
                        )
                    !!}
                </nav>
            @endif
        @else
            <p class="py-10 mb-6 text-center bg-gray-100 italic woocommerce-noreviews comment-reply-title text-sm text-sm text-gray-500">
                {!! esc_html_e( 'There are no reviews yet.', 'woocommerce' ) !!}
            </p>
        @endif
    </div>

    @if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) )
        <div id="review_form_wrapper">
            <div id="review_form">
                {{--  translators: %s opening and closing link tags respectively  --}}
                {{--  translators: %s is product title  --}}
                {{--  translators: %s is product title  --}}
                @php
                    $commenter    = wp_get_current_commenter();
                    $comment_form = array(

                        'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
                        'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
                        'title_reply_before'  => '<span id="reply-title" class="comment-reply-title text-sm font-medium text-gray-900">',
                        'title_reply_after'   => '</span>',
                        'comment_notes_after' => '',
                        'label_submit'        => esc_html__( 'Submit', 'woocommerce' ),
                        'logged_in_as'        => '',
                        'comment_field'       => '',
                    );

                    $name_email_required = (bool) get_option( 'require_name_email', 1 );
                    $fields              = array(
                        'author' => array(
                            'label'    => __( 'Name', 'woocommerce' ),
                            'type'     => 'text',
                            'value'    => $commenter['comment_author'],
                            'required' => $name_email_required,
                        ),
                        'email'  => array(
                            'label'    => __( 'Email', 'woocommerce' ),
                            'type'     => 'email',
                            'value'    => $commenter['comment_author_email'],
                            'required' => $name_email_required,
                        ),
                    );

                    $comment_form['fields'] = array();

                    foreach ( $fields as $key => $field ) {
                        $field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
                        $field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

                        if ( $field['required'] ) {
                            $field_html .= '&nbsp;<span class="required">*</span>';
                        }

                        $field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

                        $comment_form['fields'][ $key ] = $field_html;
                    }

                    $account_page_url = wc_get_page_permalink( 'myaccount' );
                    if ( $account_page_url ) {

                        $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
                    }

                    if ( wc_review_ratings_enabled() ) {
                        $comment_form['comment_field'] = view('woocommerce.single-product.rating-form');
                    }

                    $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

                    $req = get_option( 'require_name_email' );
                    $required_text = sprintf(
                        /* translators: %s: Asterisk symbol (*). */
                        ' ' . __( 'Required fields are marked %s' ),
                        '<span class="required">*</span>'
                    );

                    $comment_form['comment_notes_before'] = sprintf(
                        '<p class="comment-notes prose-sm italic mb-2">%s%s</p>',
                        sprintf(
                            '<span id="email-notes">%s</span>',
                            __( 'Your email address will not be published.' )
                        ),
                        ( $req ? $required_text : '' )
                    );
                    comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
                @endphp
            </div>
        </div>
    @else
        <p class="woocommerce-verification-required">@php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); @endphp</p>
    @endif
    <div class="clear"></div>
</div>
