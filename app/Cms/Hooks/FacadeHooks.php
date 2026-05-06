<?php

declare(strict_types=1);

namespace App\Cms\Hooks;

use Pollora\Attributes\Action;

/**
 * Test case: Hooks registered via facades in a service provider.
 *
 * This class is used as callback target for facade-based hook registration
 * in AppServiceProvider. It validates the imperative API (facades) works
 * alongside the declarative API (attributes).
 *
 * Tests:
 * - Class-based callback with auto-resolved method name
 * - Class-based callback with explicit method
 * - Closure callbacks
 * - Deferred string callbacks
 * - do()/apply()/exists()/remove()/getCallbacks()
 *
 * @see \App\Providers\AppServiceProvider::bootHookExamples()
 */
class FacadeHooks
{
    /**
     * Auto-resolved method: hook 'pollora_test_event' → method 'polloraTestEvent'.
     *
     * Tests that Pollora converts hook names to StudlyCase methods automatically
     * when a class name is passed without specifying a method.
     */
    public function polloraTestEvent(): void
    {
        // This method is called automatically when 'pollora_test_event' fires
    }

    /**
     * Explicit method target for facade registration.
     */
    public function customHandler(string $content): string
    {
        return $content . ' [facade-filtered]';
    }
}
