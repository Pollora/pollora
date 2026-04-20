<?php

declare(strict_types=1);

namespace Tests\Feature\Installation;

use Tests\Helpers\WordPressAssertions;
use Tests\TestCase;

class WordPressInstallTest extends TestCase
{
    use WordPressAssertions;

    public function test_wordpress_options_table_exists(): void
    {
        $this->assertWordPressTableExists('options');
    }

    public function test_wordpress_posts_table_exists(): void
    {
        $this->assertWordPressTableExists('posts');
    }

    public function test_wordpress_users_table_exists(): void
    {
        $this->assertWordPressTableExists('users');
    }

    public function test_siteurl_option_is_set(): void
    {
        $siteurl = $this->getWordPressOption('siteurl');

        $this->assertNotNull($siteurl, 'WordPress siteurl option is not set');
        $this->assertNotEmpty($siteurl);
    }

    public function test_home_option_is_set(): void
    {
        $home = $this->getWordPressOption('home');

        $this->assertNotNull($home, 'WordPress home option is not set');
        $this->assertNotEmpty($home);
    }

    public function test_blogname_option_is_set(): void
    {
        $blogname = $this->getWordPressOption('blogname');

        $this->assertNotNull($blogname, 'WordPress blogname option is not set');
        $this->assertNotEmpty($blogname);
    }

    public function test_admin_user_exists(): void
    {
        $users = \Illuminate\Support\Facades\DB::table('users')->count();

        $this->assertGreaterThan(0, $users, 'No admin user found in users table');
    }

    public function test_permalinks_are_set_to_postname(): void
    {
        $structure = $this->getWordPressOption('permalink_structure');

        $this->assertEquals('/%postname%/', $structure, 'Permalinks are not set to /%postname%/');
    }
}
