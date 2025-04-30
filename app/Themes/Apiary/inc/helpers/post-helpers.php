<?php

namespace App\Themes\Apiary;

/**
 * Get teaser data in nice array format.
 *
 * @param  int  $post_id
 * @param  string  $img_size
 * @return array
 */
function get_post_teaser_data($post_id, $img_size = 'thumbnail')
{
    if (empty($post_id)) {
        $post_id = get_the_id();
    }

    $img_id = get_post_thumbnail_id($post_id);
    $author_id = get_post_field('post_author', $post_id);
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_url = get_author_posts_url($author_id);

    return [
        'title' => get_the_title($post_id),
        'link' => get_permalink($post_id),
        'excerpt' => get_the_excerpt($post_id),
        'time' => get_post_time('c', true, $post_id),
        'date' => get_the_date('', $post_id),
        'author_name' => $author_name,
        'author_url' => $author_url,
        'img' => [
            'url' => get_resized_image_url($img_id, $img_size),
            'alt' => get_image_alt($img_id),
        ],
    ];
}

/**
 * Get single post data in nice array format.
 *
 * @param  int  $post_id
 * @return array
 */
function get_single_post_data($post_id = 0)
{
    if (empty($post_id)) {
        $post_id = get_the_id();
    }

    // We can get and use all teaser data
    $data = get_post_teaser_data($post_id, 'medium');

    // And add more data
    return array_merge($data, [
        'content' => apply_filters('the_content', get_post_field('post_content', $post_id, 'raw')),
    ]);
}
