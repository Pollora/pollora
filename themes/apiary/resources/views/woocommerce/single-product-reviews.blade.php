{{-- *
 * Display single product reviews (comments)
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
  --}}
@php
    global $product;

    if ( ! comments_open() ) {
        return;
    }

    $count = $product->get_review_count();
    $can_review = get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() );
@endphp
<div id="reviews" class="woocommerce-Reviews">

    {{-- Header --}}
    <div class="flex items-baseline justify-between gap-4 mb-8">
        <h2 class="text-xl font-bold text-foreground">
            @if ( $count && wc_review_ratings_enabled() )
                {!! apply_filters( 'woocommerce_reviews_title', sprintf( _n( '%s review', '%s reviews', $count, 'woocommerce' ), $count ), $count, $product ) !!}
            @else
                {{ __( 'Reviews', 'woocommerce' ) }}
            @endif
        </h2>
        @if ( $can_review && have_comments() )
            <a href="#review_form" class="woocommerce-review-link text-sm font-medium text-primary hover:text-primary-hover transition-colors">
                {{ __( 'Write a review', 'apiary' ) }}
            </a>
        @endif
    </div>

    {{-- Two-column layout: reviews left, form sticky right --}}
    <div class="lg:grid lg:grid-cols-5 lg:gap-x-12">

        {{-- Left column: reviews list --}}
        <div id="comments" class="lg:col-span-3 flex flex-col">
            @if ( have_comments() )
                <ol class="commentlist space-y-4 list-none pl-0">
                    @php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); @endphp
                </ol>
                @if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) )
                    <nav class="woocommerce-pagination mt-8">
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

                {{-- Encouragement block when few reviews — stretches to fill remaining space --}}
                @if ( $count < 5 && $can_review )
                    <div class="mt-6 rounded-xl bg-surface flex-1 flex flex-col items-center justify-center px-6 py-10 text-center">
                        <svg class="h-14 w-14 text-subtle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                        <p class="mt-4 text-base font-semibold text-foreground">{{ __( 'What do you think?', 'apiary' ) }}</p>
                        <p class="mt-1.5 text-sm text-muted">{{ sprintf( _n( 'Only %d review so far — your opinion matters.', 'Only %d reviews so far — your opinion matters.', $count, 'apiary' ), $count ) }}</p>
                        <a href="#review_form" class="woocommerce-review-link mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary-hover transition-colors">
                            {{ __( 'Write a review', 'apiary' ) }}
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 0 0 1.06 0l7.22-7.22v5.69a.75.75 0 0 0 1.5 0v-7.5a.75.75 0 0 0-.75-.75h-7.5a.75.75 0 0 0 0 1.5h5.69l-7.22 7.22a.75.75 0 0 0 0 1.06Z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                @endif
            @else
                <div class="rounded-xl bg-surface h-full min-h-[280px] py-8">
                    {{-- h-3/5 constrains the sticky zone — sticky stops near center --}}
                    <div class="h-3/5">
                        <div class="lg:sticky lg:top-[55vh] text-center px-6 py-10">
                            <svg class="mx-auto h-14 w-14 text-subtle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                            <p class="mt-4 text-base font-semibold text-foreground">{{ __( 'No reviews yet', 'woocommerce' ) }}</p>
                            <p class="mt-1.5 text-sm text-muted">{{ __( 'Be the first to share your experience.', 'apiary' ) }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right column: review form (sticky on desktop) --}}
        <div class="lg:col-span-2 mt-10 lg:mt-0">
            @if ( $can_review )
                <div id="review_form_wrapper" class="lg:sticky lg:top-6">
                    <div id="review_form" class="rounded-xl border border-outline bg-surface p-6">
                        @php
                            $commenter    = wp_get_current_commenter();
                            $comment_form = array(
                                'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
                                'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
                                'title_reply_before'  => '<span id="reply-title" class="comment-reply-title text-base font-bold text-foreground" role="heading" aria-level="3">',
                                'title_reply_after'   => '</span>',
                                'comment_notes_after' => '',
                                'label_submit'        => esc_html__( 'Submit review', 'woocommerce' ),
                                'logged_in_as'        => '',
                                'comment_field'       => '',
                            );

                            $name_email_required = (bool) get_option( 'require_name_email', 1 );
                            $fields              = array(
                                'author' => array(
                                    'label'        => __( 'Name', 'woocommerce' ),
                                    'type'         => 'text',
                                    'value'        => $commenter['comment_author'],
                                    'required'     => $name_email_required,
                                    'autocomplete' => 'name',
                                ),
                                'email'  => array(
                                    'label'        => __( 'Email', 'woocommerce' ),
                                    'type'         => 'email',
                                    'value'        => $commenter['comment_author_email'],
                                    'required'     => $name_email_required,
                                    'autocomplete' => 'email',
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
                                $comment_form['must_log_in'] = '<p class="must-log-in text-sm text-muted">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '" class="text-primary hover:text-primary-hover">', '</a>' ) . '</p>';
                            }

                            if ( wc_review_ratings_enabled() ) {
                                $comment_form['comment_field'] = view('woocommerce.single-product.rating-form');
                            }

                            $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="4" required></textarea></p>';

                            $req = get_option( 'require_name_email' );
                            $required_text = sprintf( ' ' . __( 'Required fields are marked %s' ), '<span class="required">*</span>' );

                            $comment_form['comment_notes_before'] = sprintf(
                                '<p class="comment-notes text-xs text-subtle mb-4">%s%s</p>',
                                sprintf( '<span id="email-notes">%s</span>', __( 'Your email address will not be published.' ) ),
                                ( $req ? $required_text : '' )
                            );
                            comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
                        @endphp
                    </div>
                </div>
            @else
                <p class="woocommerce-verification-required text-sm text-muted">@php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); @endphp</p>
            @endif
        </div>

    </div>
    <div class="clear"></div>
</div>
