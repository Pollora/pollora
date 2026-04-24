<?php
/**
 * Server-side rendering for the pollora-starter/call-to-action block.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 */

$heading     = $attributes['heading'] ?? '';
$description = $attributes['description'] ?? '';
$buttonText  = $attributes['buttonText'] ?? __('Learn More', 'pollora-starter');
$buttonUrl   = $attributes['buttonUrl'] ?? '#';
$alignment   = $attributes['alignment'] ?? 'center';

if (empty($heading) && empty($description)) {
    return;
}

$classes = 'has-text-align-' . esc_attr($alignment);
?>
<div <?php echo get_block_wrapper_attributes(['class' => $classes]); ?>>
    <?php if (! empty($heading)) : ?>
        <h2 class="wp-block-pollora-starter-call-to-action__heading">
            <?php echo wp_kses_post($heading); ?>
        </h2>
    <?php endif; ?>

    <?php if (! empty($description)) : ?>
        <p class="wp-block-pollora-starter-call-to-action__description">
            <?php echo wp_kses_post($description); ?>
        </p>
    <?php endif; ?>

    <?php if (! empty($buttonText)) : ?>
        <div class="wp-block-pollora-starter-call-to-action__button-wrapper">
            <a class="wp-block-pollora-starter-call-to-action__button"
               href="<?php echo esc_url($buttonUrl); ?>">
                <?php echo esc_html($buttonText); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
