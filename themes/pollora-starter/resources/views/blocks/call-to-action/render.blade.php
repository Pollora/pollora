{{--
    Server-side rendering for the pollora-starter/call-to-action block.

    Available variables:
    - array    $attributes  Block attributes
    - string   $content     Block default content
    - WP_Block $block       Block instance
--}}
@php
    $heading = $attributes['heading'] ?? '';
    $description = $attributes['description'] ?? '';
    $buttonText = $attributes['buttonText'] ?? __('Learn More', 'pollora-starter');
    $buttonUrl = $attributes['buttonUrl'] ?? '#';
    $alignment = $attributes['alignment'] ?? 'center';
@endphp

@if ($heading !== '' || $description !== '')
    <div {!! get_block_wrapper_attributes(['class' => 'has-text-align-' . $alignment]) !!}>
        @if ($heading !== '')
            <h2 class="wp-block-pollora-starter-call-to-action__heading">
                {!! wp_kses_post($heading) !!}
            </h2>
        @endif

        @if ($description !== '')
            <p class="wp-block-pollora-starter-call-to-action__description">
                {!! wp_kses_post($description) !!}
            </p>
        @endif

        @if ($buttonText !== '')
            <div class="wp-block-pollora-starter-call-to-action__button-wrapper">
                <a class="wp-block-pollora-starter-call-to-action__button" href="{{ esc_url_raw($buttonUrl) }}">
                    {{ $buttonText }}
                </a>
            </div>
        @endif
    </div>
@endif
