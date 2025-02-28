<?php

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

if (! function_exists('wc_review_comment_form_args')) {
    /**
     * Custom review comment form arguments
     */
    function wc_review_comment_form_args(array $args): array
    {
        $form_class = config('woocommerce.review.form.wrapper.class', 'mt-4 mb-6');
        $label_class = config('woocommerce.review.form.label.class', 'block text-sm font-medium text-gray-700 mb-1');
        $field_class = config('woocommerce.review.form.field.class', 'py-3');
        $textarea_class = config('woocommerce.review.form.textarea.class', 'shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md');
        $input_class = config('woocommerce.review.form.textarea.class', 'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md');
        $submit_wrapper_class = config('woocommerce.review.form.submit.wrapper.class', 'py-3 text-right');
        $submit_class = config('woocommerce.review.form.submit.class', 'inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500');

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
        $args['comment_field'] = str_replace('class="', 'class="', $args['comment_field']);
        $args['class_submit'] = $submit_class;
        $args['submit_button'] = '<div class="'.$submit_wrapper_class.'"><input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" /></div>';

        return $args;
    }
}

Filter::add('woocommerce_product_review_comment_form_args', 'wc_review_comment_form_args', 50);

Filter::add('comment_form_field_cookies', function ($output) {
    $label_class = config('woocommerce.review.form.cookie.label.class', 'text-sm font-medium text-gray-700 mb-1 ml-3');
    $wrapper_class = config('woocommerce.review.form.cookie.wrapper.class', 'py-3 flex items-start');
    $checkbox_class = config('woocommerce.review.form.cookie.checkbox.class', 'focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded');

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

// Register the comment template loader
Filter::add('comments_template', 'WC_Template_Loader::comments_template_loader');

// Remove the default avatar hook
Action::remove('woocommerce_review_before', 'woocommerce_review_display_gravatar', 10);

// Avatar view declaration
Action::add('woocommerce_review_before', function ($comment) {
    echo view('woocommerce.single-product.review-avatar', ['comment' => $comment]);
}, 10);

// Move the rating stars
Action::remove('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);
Action::add('woocommerce_review_before_comment_text', 'woocommerce_review_display_rating', 10);

// Custom product rating html
Filter::add('woocommerce_product_get_rating_html', function ($html, $rating, $count) {
    $rating = round($rating);
    echo view('woocommerce.global.rating-stars', ['rating' => $rating, 'count' => $count]);
}, 20, 3);

/**
 *  Override the woocommerce_review_display_comment_text function
 */
function woocommerce_review_display_comment_text()
{
    echo view('woocommerce.single-product.review-comment');
}
