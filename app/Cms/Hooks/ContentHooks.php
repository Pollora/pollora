<?php

declare(strict_types=1);

namespace App\Cms\Hooks;

use Pollora\Attributes\Action;
use Pollora\Attributes\Filter;

/**
 * Test case: Hooks with priority and multiple attributes on same method.
 *
 * Validates that Action/Filter attributes are properly discovered and
 * registered with correct priorities. Also tests constructor injection.
 *
 * @covers Pollora\Hook\Infrastructure\Services\HookDiscovery
 * @covers Pollora\Attributes\Action
 * @covers Pollora\Attributes\Filter
 */
class ContentHooks
{
    private string $prefix;

    public function __construct()
    {
        $this->prefix = '[Pollora Test] ';
    }

    /**
     * Filter with explicit priority.
     * Tests that priority parameter is properly passed to WordPress.
     */
    #[Filter('the_title', priority: 999)]
    public function appendTestBadge(string $title): string
    {
        if (is_admin()) {
            return $title;
        }

        // Only on test post type to avoid polluting other titles
        if (get_post_type() === 'test_case') {
            return $title . ' ✓';
        }

        return $title;
    }

    /**
     * Multiple hooks on the same method.
     * Tests that a method can be registered on multiple hooks.
     */
    #[Action('wp_head', priority: 1)]
    #[Action('admin_head', priority: 1)]
    public function addMetaGenerator(): void
    {
        echo '<!-- Pollora Test Suite Active -->' . PHP_EOL;
    }

    /**
     * Filter that modifies body classes.
     * Tests filter with array return type.
     */
    #[Filter('body_class')]
    public function addBodyClass(array $classes): array
    {
        $classes[] = 'pollora-test-active';

        return $classes;
    }
}
