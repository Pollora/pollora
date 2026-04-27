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
 * Service post type — example with translatable labels via withArgs().
 *
 * For i18n-ready labels, define them in withArgs() where __() calls
 * are evaluated at runtime. This makes them extractible by WP-CLI
 * `wp i18n make-pot` and translatable via .po/.mo files.
 *
 * Labels returned by withArgs() are merged with (and override)
 * the framework's auto-generated labels.
 */
#[PostType]
#[PubliclyQueryable]
#[HasArchive]
#[ShowInRest]
#[Supports(['title', 'editor', 'thumbnail'])]
#[MenuIcon('dashicons-hammer')]
class Service
{
    /**
     * @return array<string, mixed>
     */
    public function withArgs(): array
    {
        return [
            'labels' => [
                'name' => __('Services', 'pollora-starter'),
                'singular_name' => __('Service', 'pollora-starter'),
                'add_new' => __('Add New', 'pollora-starter'),
                'add_new_item' => __('Add New Service', 'pollora-starter'),
                'edit_item' => __('Edit Service', 'pollora-starter'),
                'all_items' => __('All Services', 'pollora-starter'),
                'search_items' => __('Search Services', 'pollora-starter'),
                'not_found' => __('No services found.', 'pollora-starter'),
            ],
        ];
    }
}
