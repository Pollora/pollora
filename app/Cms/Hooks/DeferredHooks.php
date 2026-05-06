<?php

declare(strict_types=1);

namespace App\Cms\Hooks;

use Pollora\Attributes\Action;

/**
 * Test case: Deferred callback registration.
 *
 * Validates that hooks can reference callbacks that don't exist yet
 * at registration time. This aligns with WordPress's native behavior
 * where add_action/add_filter accept any callback without validation.
 *
 * The actual function 'pollora_test_deferred_callback' is defined later
 * in this file, simulating a plugin that registers a function after
 * the hook system has initialized.
 *
 * @covers Pollora\Hook\Domain\Services\AbstractHook::resolveCallback()
 */
class DeferredHooks
{
    /**
     * Register a deferred callback via attribute.
     * This action itself registers a function-based hook via facades.
     */
    #[Action('init', priority: 999)]
    public function registerDeferredCallbacks(): void
    {
        // This uses a function name that IS defined (below) — basic string callback test
        \Pollora\Support\Facades\Action::add('pollora_test_deferred_hook', 'pollora_test_deferred_callback');

        // This uses a function name that may NOT exist yet — deferred resolution test
        // WordPress will simply skip it if not defined when the hook fires
        \Pollora\Support\Facades\Action::add('pollora_test_late_hook', 'pollora_test_maybe_later_callback');
    }
}

/**
 * A plain function callback — tests the string callback path.
 */
function pollora_test_deferred_callback(): void
{
    // This function is defined after the class, simulating deferred availability
}
