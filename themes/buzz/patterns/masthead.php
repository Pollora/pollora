<?php
/**
 * Title: Masthead
 * Slug: buzz/masthead
 * Categories: buzz/patterns
 * Block Types: core/template-part/header
 * Inserter: false
 */
?>
<!-- wp:group {"tagName":"header","align":"full","className":"buzz-masthead","style":{"spacing":{"padding":{"top":"var:preset|spacing|sm","bottom":"var:preset|spacing|sm"}}},"layout":{"type":"constrained"}} -->
<header class="wp-block-group alignfull buzz-masthead" style="padding-top:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--sm)">
    <!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
    <div class="wp-block-group alignwide">
        <!-- wp:site-title {"className":"buzz-nameplate"} /-->

        <!-- wp:navigation {"layout":{"type":"flex","justifyContent":"right"},"overlayMenu":"mobile"} /-->
    </div>
    <!-- /wp:group -->
</header>
<!-- /wp:group -->
