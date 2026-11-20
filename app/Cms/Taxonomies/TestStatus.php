<?php

declare(strict_types=1);

namespace App\Cms\Taxonomies;

use Pollora\Attributes\Taxonomy;
use Pollora\Attributes\Taxonomy\Hierarchical;
use Pollora\Attributes\Taxonomy\ShowAdminColumn;
use Pollora\Attributes\Taxonomy\ShowInRest;

/**
 * TestStatus taxonomy — validates taxonomy discovery with withArgs().
 *
 * Attached to the test_case post type. Uses withArgs() for translatable labels
 * and ShowAdminColumn to verify attribute parameter handling.
 *
 * @covers Pollora\Taxonomy\Infrastructure\Services\TaxonomyDiscovery
 * @covers Pollora\Attributes\Taxonomy
 * @covers Pollora\Attributes\Taxonomy\ShowAdminColumn
 */
#[Taxonomy(objectType: 'test_case')]
#[Hierarchical]
#[ShowInRest]
#[ShowAdminColumn]
class TestStatus
{
    /**
     * @return array<string, mixed>
     */
    public function withArgs(): array
    {
        return [
            'labels' => [
                'name' => __('Test Statuses', 'pollora-starter'),
                'singular_name' => __('Test Status', 'pollora-starter'),
                'all_items' => __('All Statuses', 'pollora-starter'),
                'edit_item' => __('Edit Status', 'pollora-starter'),
                'add_new_item' => __('Add New Status', 'pollora-starter'),
            ],
        ];
    }
}
