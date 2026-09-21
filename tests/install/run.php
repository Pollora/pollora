#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Install integration scenarios.
 *
 * Usage:  php tests/install/run.php <scenario>
 *
 *   web       install through the WordPress web wizard, scaffold a theme the
 *             way a user would, then check rendering, rewrite rules and theme
 *             resolution                            (points 1.1 → 1.4)
 *   no-theme  install through the wizard with themes/ empty: the site must
 *             answer 503 with instructions, never 500          (point 1.5)
 *   decoy     install while the target URL already answers with a Set-Cookie
 *                                                               (point 1.6)
 *   artisan   the pollora:install baseline, for comparison
 *   checks    run the checks against the site as it stands, without
 *             reinstalling; seeds install-test* fixtures, drops nothing.
 *             Takes an optional group — rendering, rewrites, theme,
 *             updates or views — to run just that one
 *
 * Every scenario but `checks` drops the database. POLLORA_INSTALL_TESTS=1 is
 * required to confirm the site is disposable.
 */

require __DIR__.'/lib.php';
require __DIR__.'/install.php';
require __DIR__.'/checks.php';

$scenario = $argv[1] ?? '';
$scenarios = ['web', 'no-theme', 'decoy', 'artisan', 'checks'];

if (! in_array($scenario, $scenarios, true)) {
    fwrite(STDERR, "\nUsage: php tests/install/run.php <".implode('|', $scenarios).">\n\n");
    exit(2);
}

$baseUrl = rtrim(getenv('POLLORA_TEST_URL') ?: 'https://pollora-test.ddev.site', '/');
$decoyUrl = rtrim(getenv('POLLORA_DECOY_URL') ?: 'http://decoy', '/');

$credentials = [
    'title' => 'Pollora install tests',
    'user' => 'admin',
    'email' => 'admin@example.com',
    'password' => 'pollora-install-tests',
];

echo "\n\033[1m=== Pollora install integration — scenario: {$scenario} ===\033[0m\n";
echo "\033[2m{$baseUrl}\033[0m\n";

try {
    switch ($scenario) {
        case 'web':
            guardDestructive($baseUrl);
            resetDatabase();
            installViaWebWizard($baseUrl, $credentials);

            // The wizard leaves no theme; the rendering checks below need one,
            // and creating it is what a user does next. Without this the whole
            // scenario measures the missing-theme page instead.
            scaffoldTheme();

            checkPageRendering();
            checkRewriteRules();
            checkThemeResolution();
            checkThemeUpdateGuard();
            checkViewPathPrecedence();
            break;

        case 'no-theme':
            guardDestructive($baseUrl);
            resetDatabase();
            removeThemes();

            // Whatever happens next, themes/ goes back where it was.
            try {
                installViaWebWizard($baseUrl, $credentials);
                checkMissingThemeGuidance();
            } finally {
                restoreThemes();
            }
            break;

        case 'decoy':
            guardDestructive($baseUrl);
            resetDatabase();
            // The decoy answers on its own host; what matters is that a
            // cookie-setting response is parsed at install time at all.
            installViaWebWizard($baseUrl, $credentials);
            checkRespondingUrlInstall($decoyUrl);
            break;

        case 'artisan':
            guardDestructive($baseUrl);
            resetDatabase();
            installViaArtisan($credentials);
            checkPageRendering();
            checkRewriteRules();
            checkThemeResolution();
            checkThemeUpdateGuard();
            checkViewPathPrecedence();
            break;

        case 'checks':
            // Non-destructive, but not read-only: it seeds a handful of
            // fixtures named install-test* so the archive pages have something
            // to show. Safe to run against a development site.
            //
            // A second argument narrows the run to one group. Checking that a
            // group still fails when its fix is reverted means running it once
            // per fix, and the full pass is far too slow for that.
            $only = $argv[2] ?? null;

            if ($only !== null && ! in_array($only, ['rendering', 'rewrites', 'theme', 'updates', 'views'], true)) {
                fwrite(STDERR, "\nUnknown group '{$only}': expected rendering, rewrites, theme, updates or views\n\n");
                exit(2);
            }

            if ($only === null || $only === 'rendering') {
                checkPageRendering();
            }

            if ($only === null || $only === 'rewrites') {
                checkRewriteRules();
            }

            if ($only === null || $only === 'theme') {
                checkThemeResolution();
            }

            if ($only === null || $only === 'updates') {
                checkThemeUpdateGuard();
            }

            if ($only === null || $only === 'views') {
                checkViewPathPrecedence();
            }
            break;
    }
} catch (\Throwable $e) {
    echo "\n\033[31mScenario aborted: {$e->getMessage()}\033[0m\n\n";
    exit(1);
}

exit(summary());
