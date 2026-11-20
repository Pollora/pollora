{{--
  Title: Colophon
  Slug: buzz/colophon
  Categories: buzz/patterns
  Block Types: core/template-part/footer
  Inserter: false
--}}
<!-- wp:group {"tagName":"footer","align":"full","className":"buzz-colophon","style":{"spacing":{"padding":{"top":"var:preset|spacing|lg","bottom":"var:preset|spacing|md"}}},"layout":{"type":"constrained"}} -->
<footer class="wp-block-group alignfull buzz-colophon" style="padding-top:var(--wp--preset--spacing--lg);padding-bottom:var(--wp--preset--spacing--md)">
    <!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|md"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group alignwide">
        <!-- wp:group {"layout":{"type":"constrained"}} -->
        <div class="wp-block-group">
            <!-- wp:site-title {"level":0,"fontSize":"lede"} /-->

            <!-- wp:site-tagline {"fontSize":"small"} /-->
        </div>
        <!-- /wp:group -->

        <!-- wp:navigation {"layout":{"type":"flex","orientation":"vertical"},"overlayMenu":"never"} /-->
    </div>
    <!-- /wp:group -->

    <!-- wp:separator {"opacity":"css","style":{"color":{"text":"#ffffff33"}},"className":"is-style-wide"} -->
    <hr class="wp-block-separator has-text-color has-alpha-channel-opacity is-style-wide" style="color:#ffffff33"/>
    <!-- /wp:separator -->

    <!-- wp:group {"align":"wide","fontSize":"caption","layout":{"type":"flex","justifyContent":"space-between"}} -->
    <div class="wp-block-group alignwide has-caption-font-size">
        <!-- wp:paragraph -->
        <p>&copy; {{ date('Y') }} {{ get_bloginfo('name') }}. All rights reserved.</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph -->
        <p>Built with <a href="https://pollora.dev">Pollora</a>.</p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</footer>
<!-- /wp:group -->
