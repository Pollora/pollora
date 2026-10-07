<?php
/**
 * Title: Journal index list
 * Slug: buzz/index-list
 * Categories: buzz/patterns
 * Inserter: false
 */
?>
<!-- wp:group {"tagName":"main","align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|lg","bottom":"var:preset|spacing|lg"}}},"layout":{"type":"constrained","contentSize":"46rem"}} -->
<main class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--lg);margin-bottom:var(--wp--preset--spacing--lg)">
    <!-- wp:query-title {"type":"archive"} /-->

    <!-- wp:query {"queryId":0,"query":{"inherit":true}} -->
    <div class="wp-block-query">
        <!-- wp:post-template {"className":"buzz-index-list"} -->
            <!-- wp:post-terms {"term":"category"} /-->

            <!-- wp:post-title {"level":2,"isLink":true,"fontSize":"headline"} /-->

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

            <!-- wp:post-featured-image {"isLink":true,"align":"wide","sizeSlug":"buzz_card","style":{"spacing":{"margin":{"top":"var:preset|spacing|xs","bottom":"var:preset|spacing|xs"}}}} /-->

            <!-- wp:post-excerpt {"moreText":""} /-->
        <!-- /wp:post-template -->

        <!-- wp:query-pagination -->
            <!-- wp:query-pagination-previous /-->

            <!-- wp:query-pagination-numbers /-->

            <!-- wp:query-pagination-next /-->
        <!-- /wp:query-pagination -->

        <!-- wp:query-no-results -->
            <!-- wp:paragraph -->
            <p>Nothing here yet.</p>
            <!-- /wp:paragraph -->
        <!-- /wp:query-no-results -->
    </div>
    <!-- /wp:query -->
</main>
<!-- /wp:group -->
