<?php

declare(strict_types=1);

namespace App\Cms\Taxonomies;

use Pollora\Attributes\Taxonomy;
use Pollora\Attributes\Taxonomy\Hierarchical;
use Pollora\Attributes\Taxonomy\Labels;
use Pollora\Attributes\Taxonomy\ShowInRest;

/**
 * Project Category taxonomy — example with #[Labels] for static overrides.
 *
 * Uses #[Labels] with named parameters for partial label overrides.
 * For translatable labels, use withArgs() (same pattern as Service post type).
 */
#[Taxonomy(objectType: 'project')]
#[Hierarchical(true)]
#[ShowInRest]
#[Labels(
    name: 'Project Categories',
    singularName: 'Project Category',
)]
class ProjectCategory
{
}