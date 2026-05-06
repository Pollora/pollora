<?php

declare(strict_types=1);

namespace App\Providers;

use App\Cms\Hooks\FacadeHooks;
use App\Cms\Hooks\InjectedHooks;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootRoute();
        $this->bootHookExamples();
    }

    public function bootRoute(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Register hook examples that exercise all documented facade capabilities.
     *
     * Each section maps to a feature described in documentation/hooks.md.
     * These run on every request to validate the hook system works end-to-end.
     */
    private function bootHookExamples(): void
    {
        // ── 1. Class-based callback with auto-resolved method name ──
        // Passing a class name alone: Pollora resolves 'pollora_test_event' → polloraTestEvent()
        Action::add('pollora_test_event', FacadeHooks::class);

        // ── 2. Class-based callback with explicit method (via resolved instance) ──
        Filter::add('pollora_test_content', [app()->make(FacadeHooks::class), 'customHandler']);

        // ── 3. Closure callback ──
        Filter::add('pollora_test_closure', function (string $value): string {
            return $value . ' [closure-filtered]';
        });

        // ── 4. Constructor injection via facade (P1 fix) ──
        // InjectedHooks has LogManager in constructor — resolved through container
        Filter::add('pollora_test_di_facade', [app()->make(InjectedHooks::class), 'filterWithDependency']);

        // ── 5. Execute custom action (do) ──
        Action::do('pollora_test_event');

        // ── 6. Apply custom filter (apply) ──
        $filtered = Filter::apply('pollora_test_content', 'original');
        // $filtered should be 'original [facade-filtered]'

        $closureFiltered = Filter::apply('pollora_test_closure', 'base');
        // $closureFiltered should be 'base [closure-filtered]'

        // ── 7. Check existence (exists) ──
        $actionExists = Action::exists('pollora_test_event');
        $filterExists = Filter::exists('pollora_test_content');
        // Both should be true

        // ── 8. Retrieve callbacks (getCallbacks) ──
        $callbacks = Filter::getCallbacks('pollora_test_content');
        // Should return an array with at least one entry

        // ── 9. Remove a hook (remove) ──
        // Register then remove a temporary hook to test removal
        $tempClosure = function (): void {};
        Action::add('pollora_test_temp', $tempClosure);
        Action::remove('pollora_test_temp', $tempClosure);
        // exists should now be false
        $removedExists = Action::exists('pollora_test_temp');

        // ── Log results for verification ──
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf(
                '[Pollora Hook Test] filtered=%s | closureFiltered=%s | actionExists=%s | filterExists=%s | removedExists=%s | callbacksCount=%d',
                $filtered,
                $closureFiltered,
                $actionExists ? 'true' : 'false',
                $filterExists ? 'true' : 'false',
                $removedExists ? 'true' : 'false',
                is_array($callbacks) ? count($callbacks) : 0
            ));
        }
    }
}
