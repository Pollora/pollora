<?php

if (! function_exists('wc_get_current_order_statuses')) {
    /**
     * Get the active order statuses
     */
    function wc_get_current_order_statuses(WC_Order $order): array
    {
        $order_statuses = wc_get_order_statuses();
        $current_status = $order->get_status();
        $final_statuses = ['completed', 'cancelled', 'refunded', 'failed'];

        if ($current_status !== 'on-hold') {
            unset($order_statuses['wc-on-hold']);
        }

        foreach ($final_statuses as $final_status) {
            if ($current_status !== $final_status) {
                if (! in_array($current_status, $final_statuses) && $final_status === 'completed') {
                    continue;
                }
                unset($order_statuses["wc-{$final_status}"]);
            }
        }

        return $order_statuses;
    }
}

if (! function_exists('wc_get_order_status_progress')) {
    /**
     * Get the percentage of the status progress of a WooCommerce Order
     */
    function wc_get_order_status_progress(WC_Order $order): int
    {
        $statuses = wc_get_current_order_statuses($order);
        $current_status = $order->get_status();
        $status_index = array_search("wc-{$current_status}", array_keys($statuses));
        $status_count = count($statuses);
        $status_quotient = $status_index / ($status_count - 1);

        return min(100, ($status_quotient * 100 + 5));
    }
}
