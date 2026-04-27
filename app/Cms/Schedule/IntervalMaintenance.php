<?php

declare(strict_types=1);

namespace App\Cms\Schedule;

use Pollora\Attributes\Schedule;
use Pollora\Schedule\Every;
use Pollora\Schedule\Interval;

/**
 * Test case: Schedule with Interval class and Every enum.
 *
 * Validates that the Interval class works correctly with totalSeconds()
 * and that the Every enum values are properly resolved to WP cron schedules.
 *
 * @covers Pollora\Schedule\Interval::totalSeconds()
 * @covers Pollora\Schedule\Interval::$display
 * @covers Pollora\Schedule\Infrastructure\Services\ScheduleDiscovery::processIntervalRecurrence()
 * @covers Pollora\Schedule\Infrastructure\Services\ScheduleDiscovery::processEveryRecurrence()
 */
class IntervalMaintenance
{
    /**
     * Custom interval: every 6 hours.
     * Tests Interval class with hours parameter.
     */
    #[Schedule(new Interval(hours: 6, display: 'Every 6 hours'))]
    public function optimizeTables(): void
    {
        global $wpdb;

        $tables = $wpdb->get_col("SHOW TABLES LIKE '{$wpdb->prefix}%'");

        foreach ($tables as $table) {
            $wpdb->query("OPTIMIZE TABLE `{$table}`");
        }
    }

    /**
     * Custom interval: every 2 hours 30 minutes.
     * Tests Interval class with mixed parameters.
     */
    #[Schedule(new Interval(hours: 2, minutes: 30, display: 'Every 2h30'))]
    public function syncExternalData(): void
    {
        // Simulates an external API sync
        update_option('last_external_sync', current_time('mysql'));
    }

    /**
     * Monthly schedule via Every enum.
     * Tests the MONTH enum value which requires custom schedule registration.
     */
    #[Schedule(Every::MONTH, hook: 'pollora_test_monthly_report')]
    public function generateMonthlyReport(): void
    {
        update_option('last_monthly_report', current_time('mysql'));
    }
}
