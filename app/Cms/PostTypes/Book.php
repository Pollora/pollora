<?php

declare(strict_types=1);

namespace App\Cms\PostTypes;

use Pollora\Attributes\PostType\HasArchive;
use Pollora\Attributes\PostType\MenuIcon;
use Pollora\Attributes\PostType\PubliclyQueryable;
use Pollora\Attributes\PostType\Supports;
use Pollora\PostType\AbstractPostType;

/**
 * Book post type.
 *
 * This class defines the Book custom post type using PHP attributes
 * for WordPress registration.
 */
#[PubliclyQueryable]
#[HasArchive]
#[Supports(['title'])]
#[MenuIcon('dashicons-admin-post')]
class Book extends AbstractPostType
{
}
