<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Tests\Helpers\WordPressAssertions;

// The WordPress* helpers live in the Tests\Helpers\WordPressAssertions trait.
// The failure messages are kept: Pest's expect() has no message argument,
// so these stay PHPUnit assertions bound to the test case.
uses(WordPressAssertions::class);

it('has the WordPress options table', function (): void {
    $this->assertWordPressTableExists('options');
});

it('has the WordPress posts table', function (): void {
    $this->assertWordPressTableExists('posts');
});

it('has the WordPress users table', function (): void {
    $this->assertWordPressTableExists('users');
});

it('has the siteurl option set', function (): void {
    $siteurl = $this->getWordPressOption('siteurl');

    $this->assertNotNull($siteurl, 'WordPress siteurl option is not set');
    expect($siteurl)->not->toBeEmpty();
});

it('has the home option set', function (): void {
    $home = $this->getWordPressOption('home');

    $this->assertNotNull($home, 'WordPress home option is not set');
    expect($home)->not->toBeEmpty();
});

it('has the blogname option set', function (): void {
    $blogname = $this->getWordPressOption('blogname');

    $this->assertNotNull($blogname, 'WordPress blogname option is not set');
    expect($blogname)->not->toBeEmpty();
});

it('has an admin user', function (): void {
    $users = DB::table('users')->count();

    $this->assertGreaterThan(0, $users, 'No admin user found in users table');
});

it('sets permalinks to postname', function (): void {
    $structure = $this->getWordPressOption('permalink_structure');

    $this->assertEquals('/%postname%/', $structure, 'Permalinks are not set to /%postname%/');
});
