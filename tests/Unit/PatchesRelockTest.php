<?php

declare(strict_types=1);

/**
 * `composer update` relocks and re-applies the dependency patches.
 *
 * composer-patches 2 reads patches.lock.json alone once it exists: it never
 * looks again at the patches a dependency declares, and applies a patch only
 * to a package installed during the current command. Measured: with a lock
 * missing the WordPress core patch, `composer install` and
 * `composer update pollora/framework` both exit 0 and leave WordPress
 * unpatched. So a framework release changing its patch would never reach an
 * existing project. Relocking after every update, then re-patching, brings it
 * in — the WordPress core is reinstalled from Composer's cache; the site's
 * wp-config.php and content live outside it.
 */

/**
 * @return list<string>
 */
function patchesRelockPostUpdateCommands(): array
{
    /** @var array{scripts?: array{post-update-cmd?: list<string>}} $composer */
    $composer = json_decode((string) file_get_contents(dirname(__DIR__, 2).'/composer.json'), true);

    return $composer['scripts']['post-update-cmd'] ?? [];
}

it('relocks the patches then reapplies them on an update', function (): void {
    $commands = patchesRelockPostUpdateCommands();
    $relock = array_search('@composer patches-relock --no-interaction', $commands, true);
    $repatch = array_search('@composer patches-repatch --no-interaction', $commands, true);

    $this->assertIsInt($relock, 'post-update-cmd does not relock the patches');
    $this->assertIsInt($repatch, 'post-update-cmd does not re-apply the patches');
    $this->assertLessThan($repatch, $relock, 'the patches must be relocked before they are re-applied');
});

it('has the patches in place before the application runs', function (): void {
    $commands = patchesRelockPostUpdateCommands();
    $repatch = array_search('@composer patches-repatch --no-interaction', $commands, true);
    $artisan = array_keys(array_filter($commands, fn (string $command): bool => str_starts_with($command, '@php artisan')));

    $this->assertIsInt($repatch);

    foreach ($artisan as $position) {
        $this->assertLessThan($position, $repatch, 'an artisan command runs before WordPress is patched again');
    }
});
