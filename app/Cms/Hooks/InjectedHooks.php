<?php

declare(strict_types=1);

namespace App\Cms\Hooks;

use Illuminate\Log\LogManager;
use Pollora\Attributes\Action;
use Pollora\Attributes\Filter;

/**
 * Test case: Constructor dependency injection in hook classes.
 *
 * Validates that classes with constructor dependencies are properly
 * resolved through the Laravel container, both via:
 * - Attribute-based discovery (HookDiscovery uses HasInstancePool → resolve())
 * - Imperative facade API (ContainerCallbackResolver → container->make())
 *
 * @covers Pollora\Hook\Infrastructure\Services\ContainerCallbackResolver
 * @covers Pollora\Hook\Infrastructure\Services\HookDiscovery
 */
class InjectedHooks
{
    public function __construct(
        private readonly LogManager $log
    ) {}

    /**
     * Action with injected dependency (attribute path).
     * Tests that LogManager is properly resolved via the container.
     */
    #[Action('wp_loaded', priority: 999)]
    public function logLoaded(): void
    {
        $this->log->debug('[Pollora Test] wp_loaded fired — InjectedHooks is alive with DI');
    }

    /**
     * Filter with injected dependency (attribute path).
     * Tests that the dependency is available when filtering.
     */
    #[Filter('pollora_test_di_filter')]
    public function filterWithDependency(string $value): string
    {
        // Prove the dependency was injected by using it
        $this->log->debug('[Pollora Test] DI filter applied');

        return $value . ' [di-injected]';
    }
}
