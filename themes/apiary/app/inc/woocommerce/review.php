<?php

declare(strict_types=1);

/**
 * WooCommerce product review form and display customizations.
 *
 * Global scope is intentional — functions are WooCommerce overrides or passed
 * as string callbacks to filters (e.g. 'wc_review_comment_form_args').
 *
 * @package Theme\Apiary
 */

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

if (! function_exists('wc_review_comment_form_args')) {
    /**
     * Customize the product review form with Tailwind-styled inputs, labels, and submit button.
     *
     * Reads CSS classes from `config/woocommerce.php` under the `review.form` key
     * and injects them into the default WordPress comment form markup via string replacement.
     *
     * @param  array  $args  Default `comment_form()` arguments.
     * @return array Modified arguments with themed classes applied.
     *
     * @see config('theme.woocommerce.review.form')
     */
    function wc_review_comment_form_args(array $args): array
    {
        $form_class = config('theme.woocommerce.review.form.wrapper.class', 'mt-4 mb-6');
        $label_class = config('theme.woocommerce.review.form.label.class', 'block text-sm font-medium text-muted mb-1');
        $field_class = config('theme.woocommerce.review.form.field.class', 'py-3');
        $textarea_class = config('theme.woocommerce.review.form.textarea.class', 'px-3.5 py-2.5 bg-white-gray mt-1 block w-full sm:text-sm border border-outline rounded-md');
        $input_class = config('theme.woocommerce.review.form.input.class', 'px-3.5 py-2.5 mt-1 bg-white-gray block w-full sm:text-sm border border-outline rounded-md');
        $submit_wrapper_class = config('theme.woocommerce.review.form.submit.wrapper.class', 'pt-4');
        $submit_class = config('theme.woocommerce.review.form.submit.class', 'w-full sm:w-auto inline-flex justify-center py-2.5 px-6 border border-transparent shadow-xs text-sm font-semibold rounded-lg text-white bg-primary hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors');

        $args['class_form'] = 'comment-form '.$form_class;

        $search = [
            '<label for="',
            'class="comment-form-',
            '<textarea',
            'type="text"',
            'type="email"',
        ];
        $replace = [
            '<label class="'.$label_class.'" for="',
            'class="'.$field_class.' comment-form-',
            '<textarea class="'.$textarea_class.'"',
            'type="text" class="'.$input_class.'"',
            'type="email" class="'.$input_class.'"',
        ];

        foreach (['email', 'author', 'url', 'cookies'] as $field) {
            if (! isset($args['fields'][$field])) {
                continue;
            }
            $args['fields'][$field] = str_replace($search, $replace, $args['fields'][$field]);
        }

        $args['comment_field'] = str_replace($search, $replace, $args['comment_field']);
        $args['class_submit'] = $submit_class;
        $args['submit_button'] = '<div class="'.$submit_wrapper_class.'"><input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" /></div>';

        return $args;
    }
}

Filter::add('woocommerce_product_review_comment_form_args', 'wc_review_comment_form_args', 50);

// Style the "Save my name/email" cookie consent checkbox with theme classes
Filter::add('comment_form_field_cookies', function ($output) {
    $label_class = config('theme.woocommerce.review.form.cookie.label.class', 'text-sm font-medium text-muted mb-1 ml-3');
    $wrapper_class = config('theme.woocommerce.review.form.cookie.wrapper.class', 'py-3 flex items-start');
    $checkbox_class = config('theme.woocommerce.review.form.cookie.checkbox.class', 'focus:ring-ring h-4 w-4 text-primary border-outline rounded-xs');

    $search = [
        '<label for="',
        '<p class="',
        '</p>',
        'type="checkbox"',
    ];
    $replace = [
        '<label class="'.$label_class.'" for="',
        '<div class="'.$wrapper_class.' ',
        '</div>',
        'type="checkbox" class="'.$checkbox_class.'"',
    ];

    return str_replace($search, $replace, $output);
});

// Ensure WooCommerce's comment template loader is used for product reviews
Filter::add('comments_template', [WC_Template_Loader::class, 'comments_template_loader']);

// Replace the default gravatar with a custom Blade avatar partial
Action::add('woocommerce_init', function () {
    Action::remove('woocommerce_review_before', 'woocommerce_review_display_gravatar', 10);
    Action::add('woocommerce_review_before', function ($comment) {
        echo view('woocommerce.single-product.review-avatar', ['comment' => $comment]);
    }, 10);
}, 10);

// Move star rating from before the comment meta to before the comment text
Action::add('woocommerce_init', function () {
    Action::remove('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);
    Action::add('woocommerce_review_before_comment_text', 'woocommerce_review_display_rating', 10);
});

// Replace the default star rating HTML with a Blade-rendered SVG star component
Filter::add('woocommerce_product_get_rating_html', function ($html, $rating, $count) {
    $rating = round((float) $rating);
    return view('woocommerce.global.rating-stars', ['rating' => $rating, 'count' => $count])->render();
}, 20, 3);

/**
 * Render the review comment text via a Blade partial.
 *
 * Overrides WooCommerce's default `woocommerce_review_display_comment_text()`
 * to use `woocommerce.single-product.review-comment`.
 */
function woocommerce_review_display_comment_text()
{
    echo view('woocommerce.single-product.review-comment');
}
