<?php

declare(strict_types=1);

namespace App\Cms\Schedule;

use Pollora\Attributes\Schedule;
use Pollora\Schedule\Every;

/**
 * Example scheduled task — discovered automatically by Pollora.
 *
 * Uses the #[Schedule] attribute with the Every enum for recurrence.
 * This class appears in the Pollora dashboard under "Scheduled Tasks".
 */
final class CacheCleanup
{
    #[Schedule(Every::DAY)]
    public function cleanExpiredTransients(): void
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                'DELETE FROM %i WHERE option_name LIKE %s AND option_value < %d',
                $wpdb->options,
                $wpdb->esc_like('_transient_timeout_').'%',
                time()
            )
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
