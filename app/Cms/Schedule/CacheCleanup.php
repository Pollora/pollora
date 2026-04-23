<?php

declare(strict_types=1);

namespace App\Cms\Schedule;

use Pollora\Attributes\Schedule;
use Pollora\Schedule\Domain\Enums\Every;

/**
 * Example scheduled task — discovered automatically by Pollora.
 *
 * Uses the #[Schedule] attribute with the Every enum for recurrence.
 * This class appears in the Pollora dashboard under "Scheduled Tasks".
 */
class CacheCleanup
{
    #[Schedule(Every::DAY)]
    public function cleanExpiredTransients(): void
    {
        global $wpdb;

        $wpdb->query(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()"
        );
    }

    #[Schedule(Every::WEEK)]
    public function cleanRevisions(): void
    {
        $revisions = get_posts([
            'post_type' => 'revision',
            'posts_per_page' => 100,
            'post_status' => 'any',
        ]);

        foreach ($revisions as $revision) {
            wp_delete_post_revision($revision->ID);
        }
    }
}
