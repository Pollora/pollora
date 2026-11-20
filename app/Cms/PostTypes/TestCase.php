<?php

declare(strict_types=1);

namespace App\Cms\PostTypes;

use Pollora\Attributes\PostType;
use Pollora\Attributes\PostType\HasArchive;
use Pollora\Attributes\PostType\MenuIcon;
use Pollora\Attributes\PostType\PubliclyQueryable;
use Pollora\Attributes\PostType\ShowInRest;
use Pollora\Attributes\PostType\Supports;

/**
 * TestCase post type — validates PostType discovery and withArgs().
 *
 * Uses withArgs() for translatable labels, which is the recommended
 * approach for i18n (instead of configuring() which receives an entity object).
 *
 * @covers Pollora\PostType\Infrastructure\Services\PostTypeDiscovery
 * @covers Pollora\Attributes\PostType
 */
#[PostType('test_case')]
#[PubliclyQueryable(false)]
#[HasArchive(false)]
#[ShowInRest]
#[Supports(['title', 'editor', 'custom-fields'])]
#[MenuIcon('dashicons-yes-alt')]
class TestCase
{
    /**
     * @return array<string, mixed>
     */
    public function withArgs(): array
    {
        return [
            'labels' => [
                'name' => __('Test Cases', 'pollora-starter'),
                'singular_name' => __('Test Case', 'pollora-starter'),
                'add_new' => __('Add Test Case', 'pollora-starter'),
                'edit_item' => __('Edit Test Case', 'pollora-starter'),
                'all_items' => __('All Test Cases', 'pollora-starter'),
                'menu_name' => __('Test Cases', 'pollora-starter'),
            ],
        ];
    }
}
