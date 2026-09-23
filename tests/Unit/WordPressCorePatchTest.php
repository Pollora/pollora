<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * WordPress core carries the framework's l10n patch.
 *
 * WordPress defines a global `__()`; so does Laravel. pollora/framework ships
 * patches/wordpress-core.patch, which renames WordPress's to `__wp()` so that
 * pollora/helper-overrider can own `__()`. The skeleton lets dependencies patch
 * their siblings through composer-patches' `enable-patching`.
 *
 * Nothing else checked that the patch actually landed, and it can fail to in
 * silence: composer-patches skips a patch that does not apply with nothing
 * more than "Could not apply patch! Skipping." — a warning, not an error. A
 * WordPress release that moves the hunk, a patch URL that stops answering, or
 * a composer-patches upgrade that stops honouring `enable-patching` would all
 * install a site with two functions named `__()` fighting over translations,
 * and every other test would stay green.
 */
class WordPressCorePatchTest extends TestCase
{
    private function l10nFile(): string
    {
        $root = dirname(__DIR__, 2);

        /** @var array{extra?: array{wordpress-install-dir?: string}} $composer */
        $composer = json_decode((string) file_get_contents($root.'/composer.json'), true);
        $installDir = $composer['extra']['wordpress-install-dir'] ?? 'public/cms';

        return $root.'/'.$installDir.'/wp-includes/l10n.php';
    }

    public function test_wordpress_core_is_installed_where_the_patch_expects_it(): void
    {
        $this->assertFileExists(
            $this->l10nFile(),
            'WordPress core is not installed, so whether it was patched cannot be told.'
        );
    }

    public function test_wordpress_translation_function_is_renamed_to_make_room_for_laravel(): void
    {
        $source = (string) file_get_contents($this->l10nFile());

        $this->assertMatchesRegularExpression(
            '/^function __wp\(/m',
            $source,
            'wp-includes/l10n.php defines no __wp(): the framework\'s wordpress-core.patch did not land.'
        );
    }

    public function test_wordpress_no_longer_declares_the_global_it_would_share_with_laravel(): void
    {
        $source = (string) file_get_contents($this->l10nFile());

        $this->assertDoesNotMatchRegularExpression(
            '/^function __\(/m',
            $source,
            'wp-includes/l10n.php still declares __(): WordPress is unpatched and collides with Laravel\'s helper.'
        );
    }
}
