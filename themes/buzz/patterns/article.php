<?php
/**
 * Title: Article
 * Slug: buzz/article
 * Categories: buzz/patterns
 * Inserter: false
 */
?>
<!-- wp:group {"tagName":"main","align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|lg","bottom":"var:preset|spacing|lg"}}},"layout":{"type":"constrained","contentSize":"42rem"}} -->
<main class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--lg);margin-bottom:var(--wp--preset--spacing--lg)">
    <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} -->
    <div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--md)">
        <!-- wp:post-terms {"term":"category"} /-->

        <!-- wp:post-title {"level":1} /-->

        <!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
        <div class="wp-block-group">
            <!-- wp:post-author-name /-->

            <!-- wp:paragraph {"fontSize":"caption"} -->
            <p class="has-caption-font-size">&middot;</p>
            <!-- /wp:paragraph -->

            <!-- wp:post-date /-->

            <!-- wp:paragraph {"fontSize":"caption"} -->
            <p class="has-caption-font-size">&middot;</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"buzz/article","args":{"field":"reading_time"}}}},"fontSize":"caption"} -->
            <p class="has-caption-font-size">Reading time</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->

    <!-- wp:post-featured-image {"align":"wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|md"}}}} /-->

    <!-- wp:post-content {"layout":{"type":"constrained"}} /-->

    <!-- wp:separator {"className":"is-style-wide"} -->
    <hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
    <!-- /wp:separator -->

    <!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|xs"}}},"layout":{"type":"flex"}} -->
    <div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--xs)">
        <!-- wp:post-terms {"term":"post_tag","prefix":"Filed under: "} /-->
    </div>
    <!-- /wp:group -->

    <!-- wp:comments -->
    <div class="wp-block-comments">
        <!-- wp:comments-title /-->

        <!-- wp:comment-template -->
            <!-- wp:columns -->
            <div class="wp-block-columns">
                <!-- wp:column {"width":"40px"} -->
                <div class="wp-block-column" style="flex-basis:40px">
                    <!-- wp:avatar {"size":40,"style":{"border":{"radius":"999px"}}} /-->
                </div>
                <!-- /wp:column -->

                <!-- wp:column -->
                <div class="wp-block-column">
                    <!-- wp:comment-author-name {"fontSize":"small"} /-->

                    <!-- wp:comment-date {"fontSize":"caption"} /-->

                    <!-- wp:comment-content /-->

                    <!-- wp:comment-reply-link {"fontSize":"caption"} /-->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->
        <!-- /wp:comment-template -->

        <!-- wp:comments-pagination -->
            <!-- wp:comments-pagination-previous /-->

            <!-- wp:comments-pagination-numbers /-->

            <!-- wp:comments-pagination-next /-->
        <!-- /wp:comments-pagination -->

        <!-- wp:post-comments-form /-->
    </div>
    <!-- /wp:comments -->
</main>
<!-- /wp:group -->
