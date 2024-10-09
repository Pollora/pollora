{{--
  Title: Un exemple
  Slug: theme/example
  Categories: theme/patterns
--}}


<!-- wp:group {"className":"pattern-example"} -->
<div class="wp-block-group pattern-example">
    <!-- wp:heading {"textAlign":"center","textColor":"tertiary"} -->
    <h2 class="has-text-align-center has-tertiary-color has-text-color">{{ get_bloginfo('title') }}</h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","textColor":"primary"} -->
    <p class="has-text-align-center has-primary-color has-text-color">{{ get_bloginfo('description') }}</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
    <div class="wp-block-buttons">
        <!-- wp:button {"backgroundColor":"tertiary","style":{"border":{"radius":"100px"}},"className":"is-style-fill"} -->
        <div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-tertiary-background-color has-background" style="border-radius:100px">Read more...</a></div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->