<?php

declare(strict_types=1);

/**
 * Install drivers.
 *
 * Every scenario starts from an empty database, so these helpers are
 * destructive by design. They refuse to run anywhere that does not look like a
 * throwaway development or CI site — see guardDestructive().
 */

function run(string $command, ?string $cwd = null): array
{
    $descriptors = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
    $process = proc_open($command.' 2>&1', $descriptors, $pipes, $cwd ?? base_path());

    if (! is_resource($process)) {
        throw new \RuntimeException("could not run: {$command}");
    }

    $out = stream_get_contents($pipes[1]) ?: '';
    fclose($pipes[1]);
    fclose($pipes[2]);

    return ['code' => proc_close($process), 'out' => trim($out)];
}

/**
 * Refuse to wipe anything that is not obviously disposable.
 *
 * Dropping the database is the price of testing an install, but the same
 * script sits in a repository people run locally. Two independent gates: an
 * explicit opt-in, and a site URL that cannot be production.
 */
function guardDestructive(string $url): void
{
    if (getenv('POLLORA_INSTALL_TESTS') !== '1') {
        fwrite(STDERR, "\n\033[31mRefusing to run: these scenarios drop the database.\033[0m\n");
        fwrite(STDERR, "Set POLLORA_INSTALL_TESTS=1 to confirm the site is disposable.\n\n");
        exit(2);
    }

    $host = parse_url($url, PHP_URL_HOST) ?: '';
    $disposable = str_ends_with($host, '.ddev.site')
        || str_ends_with($host, '.test')
        || str_ends_with($host, '.localhost')
        || in_array($host, ['localhost', '127.0.0.1'], true);

    if (! $disposable) {
        fwrite(STDERR, "\n\033[31mRefusing to run against {$host}.\033[0m\n");
        fwrite(STDERR, "The install scenarios only run against a local or CI host.\n\n");
        exit(2);
    }
}

/**
 * Drop every table and put the Laravel schema back.
 *
 * The Laravel side has to be migrated before WordPress is installed: the cache
 * table is read during the very first request that follows.
 */
function resetDatabase(): void
{
    echo "  \033[2m→ dropping the database\033[0m\n";
    $reset = run('wp db reset --yes');

    if ($reset['code'] !== 0) {
        throw new \RuntimeException("wp db reset failed: {$reset['out']}");
    }

    echo "  \033[2m→ running Laravel migrations\033[0m\n";
    $migrate = run('php artisan migrate --force --no-interaction');

    if ($migrate['code'] !== 0) {
        throw new \RuntimeException("artisan migrate failed: {$migrate['out']}");
    }
}

/**
 * Empty themes/ so the site genuinely has no theme to render with.
 *
 * Themes are moved aside rather than deleted, and restoreThemes() puts them
 * back. A test has no business destroying a working tree it could set aside —
 * a scaffolded theme is hours of someone's work, and this scenario runs on
 * development machines, not only in throwaway CI containers.
 *
 * .gitkeep stays, so themes/ looks exactly as a fresh checkout does before
 * pollora:install runs.
 */
function themesHoldingArea(): string
{
    return base_path().'/storage/framework/testing/themes-set-aside';
}

function removeThemes(): void
{
    $holding = themesHoldingArea();
    echo "  \033[2m→ moving themes/ aside\033[0m\n";

    if (is_dir($holding)) {
        throw new \RuntimeException(
            "{$holding} already exists: a previous run did not restore themes/. "
            .'Move its contents back into themes/ before running this again.'
        );
    }

    run('mkdir -p '.escapeshellarg($holding));

    foreach (glob(base_path().'/themes/*') ?: [] as $path) {
        if (is_dir($path)) {
            run('mv '.escapeshellarg($path).' '.escapeshellarg($holding.'/'));
        }
    }
}

/**
 * Put back whatever removeThemes() set aside. Safe to call when it did not.
 */
function restoreThemes(): void
{
    $holding = themesHoldingArea();

    if (! is_dir($holding)) {
        return;
    }

    echo "  \033[2m→ moving themes/ back\033[0m\n";

    foreach (glob($holding.'/*') ?: [] as $path) {
        run('mv '.escapeshellarg($path).' '.escapeshellarg(base_path().'/themes/'));
    }

    run('rmdir '.escapeshellarg($holding));
}

/**
 * Point 1.1 — install WordPress the way the web wizard does.
 *
 * This is the path four of the six beta.3 fixes came from, and the one no test
 * walked: `pollora:install` and this wizard leave the site in measurably
 * different states.
 */
function installViaWebWizard(string $baseUrl, array $credentials): void
{
    echo "  \033[2m→ installing through the web wizard\033[0m\n";

    $step1 = http($baseUrl.'/cms/wp-admin/install.php');

    if ($step1['status'] !== 200) {
        throw new \RuntimeException("the installer did not answer: HTTP {$step1['status']}");
    }

    if (str_contains($step1['body'], 'Already Installed') || str_contains($step1['body'], 'already installed')) {
        throw new \RuntimeException('WordPress is already installed — the reset did not take');
    }

    $response = http($baseUrl.'/cms/wp-admin/install.php?step=2', [
        'weblog_title' => $credentials['title'],
        'user_name' => $credentials['user'],
        'admin_email' => $credentials['email'],
        'admin_password' => $credentials['password'],
        'admin_password2' => $credentials['password'],
        'pw_weak' => '1',
        'blog_public' => '0',
        'language' => '',
    ]);

    if ($response['status'] !== 200) {
        throw new \RuntimeException("the wizard answered HTTP {$response['status']}");
    }

    $succeeded = str_contains($response['body'], 'wp-login.php')
        || stripos($response['body'], 'Success') !== false;

    if (! $succeeded) {
        $excerpt = trim(strip_tags($response['body']));
        throw new \RuntimeException('the wizard did not report success: '.substr($excerpt, 0, 300));
    }

    // The wizard leaves plain permalinks. A real user goes to Settings →
    // Permalinks next, which is exactly the moment fix 5 matters, so the
    // scenario does the same thing rather than pre-baking the option.
    run('wp rewrite structure '.escapeshellarg('/%postname%/').' --hard');
}

/**
 * The install path the CI workflow already covered, kept as the baseline the
 * other scenarios are compared against.
 */
function installViaArtisan(array $credentials): void
{
    echo "  \033[2m→ installing through pollora:install\033[0m\n";

    $result = run('php artisan pollora:install --install'
        .' --title='.escapeshellarg($credentials['title'])
        .' --admin-user='.escapeshellarg($credentials['user'])
        .' --admin-email='.escapeshellarg($credentials['email'])
        .' --admin-password='.escapeshellarg($credentials['password'])
        .' --locale=en_US --public=false --no-interaction');

    if ($result['code'] !== 0) {
        throw new \RuntimeException("pollora:install failed: {$result['out']}");
    }
}
