<?php

declare(strict_types=1);

namespace App\Cms\PostTypes;

use Pollora\Attributes\PostType;
use Pollora\Attributes\PostType\HasArchive;
use Pollora\Attributes\PostType\Labels;
use Pollora\Attributes\PostType\MenuIcon;
use Pollora\Attributes\PostType\PubliclyQueryable;
use Pollora\Attributes\PostType\ShowInRest;
use Pollora\Attributes\PostType\Supports;

/**
 * Project post type — example with #[Labels] for static label overrides.
 *
 * The #[Labels] attribute accepts named parameters and merges them
 * with the auto-generated labels. Only the labels you specify
 * are overridden; the rest keep their defaults.
 *
 * Note: PHP attributes only accept constant expressions, so __()
 * cannot be used here. For translatable labels, use withArgs() instead
 * (see Service post type for an example).
 */
#[PostType]
#[PubliclyQueryable]
#[HasArchive]
#[ShowInRest]
#[Supports(['title', 'editor', 'thumbnail', 'excerpt'])]
#[MenuIcon('dashicons-portfolio')]
#[Labels(
    addNew: 'New Project',
    notFound: 'No projects found.',
    notFoundInTrash: 'No projects found in trash.',
)]
class Project
{
}