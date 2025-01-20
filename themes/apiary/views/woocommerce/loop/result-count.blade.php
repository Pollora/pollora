{{--
    Result Count Blade Template
    Shows text: Showing x - x of x results.

    This template is a Blade adaptation of the original WooCommerce template.

    @package     WooCommerce\Templates
    @version     9.4.0
--}}

@php
    if (!isset($total) || !isset($per_page) || !isset($current)) {
        return;
    }
    $first = ($per_page * $current) - $per_page + 1;
    $last = min($total, $per_page * $current);
@endphp

<div class="woocommerce-result-count text-sm" @if(empty($orderedby) || $total == 1) @else role="alert" aria-relevant="all" data-is-sorted-by="true" @endif>
    @if ($total == 1)
        {{ __('Showing the single result', 'woocommerce') }}
    @elseif ($total <= $per_page || $per_page == -1)
        @php
            $orderedby_placeholder = empty($orderedby) ? '%2$s' : '<span class="screen-reader-text">%2$s</span>';
        @endphp
        {!! sprintf(
            _n('Showing all %1$d result', 'Showing all %1$d results', $total, 'woocommerce') . $orderedby_placeholder,
            $total,
            e($orderedby)
        ) !!}
    @else
        @php
            $orderedby_placeholder = empty($orderedby) ? '%4$s' : '<span class="screen-reader-text">%4$s</span>';
        @endphp
        {!! sprintf(
            _nx(
                'Showing %1$d&ndash;%2$d of %3$d result',
                'Showing %1$d&ndash;%2$d of %3$d results',
                $total,
                'with first and last result',
                'woocommerce'
            ) . $orderedby_placeholder,
            $first,
            $last,
            $total,
            e($orderedby)
        ) !!}
    @endif
</div>
