<?php

declare(strict_types=1);

/**
 * WordPress core carries the framework's l10n patch.
 *
 * WordPress defines a global `__()`; so does Laravel. pollora/framework ships
 * patches/wordpress-core.patch, which renames WordPress's to `__wp()` so that
 * pollora/helper-overrider can own `__()`. composer-patches 2 applies the
 * patches a dependency declares for its siblings, unconditionally.
 *
 * Nothing else checked that the patch actually landed, and it can fail to in
 * silence: composer-patches skips a patch that does not apply with nothing
 * more than "Could not apply patch! Skipping." — a warning, not an error. A
 * WordPress release that moves the hunk, a patch URL that stops answering, or
 * a patches.lock.json that no longer lists the patch would all
 * install a site with two functions named `__()` fighting over translations,
 * and every other test would stay green.
 */
function wordPressCorePatchL10nFile(): string
{
    $root = dirname(__DIR__, 2);

    /** @var array{extra?: array{wordpress-install-dir?: string}} $composer */
    $composer = json_decode((string) file_get_contents($root.'/composer.json'), true);
    $installDir = $composer['extra']['wordpress-install-dir'] ?? 'public/cms';

    return $root.'/'.$installDir.'/wp-includes/l10n.php';
}

it('has WordPress core installed where the patch expects it', function (): void {
    $this->assertFileExists(
        wordPressCorePatchL10nFile(),
        'WordPress core is not installed, so whether it was patched cannot be told.'
    );
});

it('renames the WordPress translation function to make room for Laravel', function (): void {
    $source = (string) file_get_contents(wordPressCorePatchL10nFile());

    $this->assertMatchesRegularExpression(
        '/^function __wp\(/m',
        $source,
        'wp-includes/l10n.php defines no __wp(): the framework\'s wordpress-core.patch did not land.'
    );
});

it('no longer declares in WordPress the global it would share with Laravel', function (): void {
    $source = (string) file_get_contents(wordPressCorePatchL10nFile());

    $this->assertDoesNotMatchRegularExpression(
        '/^function __\(/m',
        $source,
        'wp-includes/l10n.php still declares __(): WordPress is unpatched and collides with Laravel\'s helper.'
    );
});
