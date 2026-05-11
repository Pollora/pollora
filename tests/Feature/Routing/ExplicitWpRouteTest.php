<?php

declare(strict_types=1);

namespace Tests\Feature\Routing;

use Illuminate\Support\Facades\DB;
use Tests\Helpers\WordPressAssertions;
use Tests\TestCase;

/**
 * Test explicit WordPress routes registered via Route::wp() in routes/web.php.
 *
 * These tests verify that each Route::wp() declaration resolves to the correct
 * Blade template by checking for the data-pollora-template marker in the response.
 *
 * Registered routes:
 *   - Route::wp('home')             → home.blade.php
 *   - Route::wp('singular', 'post') → post.blade.php (only for post type 'post')
 *   - Route::wp('page')             → page.blade.php
 *
 * Prerequisites:
 *   - WordPress installed with sample content (at least one published post and page)
 *   - Permalinks set to /%postname%/
 *   - Theme pollora-starter active with data-pollora-template markers
 */
class ExplicitWpRouteTest extends TestCase
{
    use WordPressAssertions;

    /**
     * Route::wp('home') must render home.blade.php on the homepage.
     */
    public function test_homepage_renders_home_template(): void
    {
        $this->assertTemplateIs('/', 'home', 200);
    }

    /**
     * Route::wp('home') must render the home view with its template marker.
     */
    public function test_homepage_contains_expected_content(): void
    {
        $this->assertTemplateIs('/', 'home', 200);
    }

    /**
     * Route::wp('page') must render page.blade.php for a published WordPress page.
     */
    public function test_page_renders_page_template(): void
    {
        $slug = $this->getFirstPublishedPageSlug();

        if ($slug === null) {
            $this->markTestSkipped('No published page found.');
        }

        $this->assertTemplateIs('/' . $slug . '/', 'page', 200);
    }

    /**
     * Route::wp('singular', 'post') must render post.blade.php for a published post.
     */
    public function test_single_post_renders_single_template(): void
    {
        $slug = $this->getFirstPublishedPostSlug();

        if ($slug === null) {
            $this->markTestSkipped('No published post found.');
        }

        $this->assertTemplateIs('/' . $slug . '/', 'single', 200);
    }

    /**
     * Route::wp('singular', 'post') must NOT match custom post types.
     * Projects should be handled by the template hierarchy, not the explicit route.
     */
    public function test_single_post_route_does_not_match_custom_post_type(): void
    {
        $slug = DB::table('posts')
            ->where('post_type', 'project')
            ->where('post_status', 'publish')
            ->value('post_name');

        if ($slug === null) {
            $this->markTestSkipped('No published project found — run: wp starter-seed create');
        }

        // The project must NOT render the generic "single" (post) template
        $this->assertTemplateIsNot('/project/' . $slug . '/', 'single');
        // It must render single-project template via template hierarchy
        $this->assertTemplateIs('/project/' . $slug . '/', 'single-project', 200);
    }

    /**
     * A non-existent URL must render 404.blade.php via template hierarchy.
     */
    public function test_nonexistent_url_renders_404_template(): void
    {
        $this->assertTemplateIs('/this-page-absolutely-does-not-exist-' . time() . '/', '404', 404);
    }

    /**
     * The 404 page must contain the "Page not found" message from the view.
     */
    public function test_404_contains_expected_content(): void
    {
        $this->assertResponseContains('/this-page-does-not-exist-ever/', 'Page not found');
    }

    /**
     * Ensure that a page URL does NOT accidentally render the home template.
     */
    public function test_page_does_not_render_home_template(): void
    {
        $slug = $this->getFirstPublishedPageSlug();

        if ($slug === null) {
            $this->markTestSkipped('No published page found.');
        }

        $this->assertTemplateIsNot('/' . $slug . '/', 'home');
    }

    /**
     * Ensure that a single post URL does NOT render the page template.
     */
    public function test_single_post_does_not_render_page_template(): void
    {
        $slug = $this->getFirstPublishedPostSlug();

        if ($slug === null) {
            $this->markTestSkipped('No published post found.');
        }

        $this->assertTemplateIsNot('/' . $slug . '/', 'page');
    }

    // ─── Helpers ─────────────────────────────────────────────

    private function getFirstPublishedPageSlug(): ?string
    {
        return DB::table('posts')
            ->where('post_type', 'page')
            ->where('post_status', 'publish')
            ->value('post_name');
    }

    private function getFirstPublishedPostSlug(): ?string
    {
        return DB::table('posts')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->value('post_name');
    }
}
