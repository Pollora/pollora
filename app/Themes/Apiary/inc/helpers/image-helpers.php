<?php

namespace App\Themes\Apiary;

/**
 * Wrapper for Image Processing Queue function.
 *
 * @param  int  $post_id
 * @param  mixed  $size
 * @return string Img URL
 */
function get_resized_image_url(int $img_id, $size)
{
    // Fallback or check if $size is string (it means size should be already generated)
    if (! function_exists('ipq_get_theme_image_url') || is_string($size)) {
        return wp_get_attachment_image_url($img_id, $size);
    }

    return ipq_get_theme_image_url($img_id, $size);
}

/**
 * Get image alt attribute
 *
 * @return string Img ALT
 */
function get_image_alt(int $img_id)
{
    return get_post_meta($img_id, '_wp_attachment_image_alt', true);
}
