<?php

declare(strict_types=1);

namespace Tests\Feature\Routing;

use Illuminate\Support\Facades\DB;
use Tests\Helpers\WordPressAssertions;
use Tests\TestCase;

/**
 * Test the WordPress template hierarchy fallback via FrontendController.
 *
 * When no explicit Route::wp() matches, the FrontendController falls back
 * to WordPress template hierarchy. These tests verify that the correct
 * Blade template is resolved for each WordPress condition (category, archive,
 * search, author, custom post type).
 *
 * Prerequisites:
 *   - WordPress installed with content: posts with categories/tags, projects
 *   - Permalinks set to /%postname%/
 *   - Theme with template hierarchy Blade views and data-pollora-template markers
 *
 * @covers \Pollora\Route\UI\Http\Controllers\FrontendController
 * @covers \Pollora\View\Infrastructure\Services\WordPressTemplateHierarchyFilter
 * @covers \Pollora\View\Infrastructure\Services\FileSystemTemplateFinder
 */
class TemplateHierarchyTest extends TestCase
{
    use WordPressAssertions;

    // ─── Category template ───────────────────────────────────

    /**
     * A category archive URL must resolve to category.blade.php, not archive.blade.php.
     *
     * WordPress hierarchy: category-{slug} → category-{id} → category → archive → index.
     * Since we have category.blade.php, it must win over archive.blade.php.
     */
    public function test_category_archive_renders_category_template(): void
    {
        $slug = $this->getFirstCategorySlug();

        if ($slug === null) {
            $this->markTestSkipped('No category with posts found — create a post with a category.');
        }

        $this->assertTemplateIs('/category/' . $slug . '/', 'category', 200);
    }

    /**
     * The category page must NOT render the generic archive template.
     */
    public function test_category_archive_does_not_render_archive_template(): void
    {
        $slug = $this->getFirstCategorySlug();

        if ($slug === null) {
            $this->markTestSkipped('No category found.');
        }

        $this->assertTemplateIsNot('/category/' . $slug . '/', 'archive');
    }

    // ─── Tag archive (falls to archive.blade.php) ───────────

    /**
     * A tag archive URL should resolve to archive.blade.php since we have
     * no tag-specific template.
     *
     * WordPress hierarchy: tag-{slug} → tag-{id} → tag → archive → index.
     * We don't have tag.blade.php, so it should fall to archive.blade.php.
     */
    public function test_tag_archive_renders_archive_template(): void
    {
        $slug = $this->getFirstTagSlug();

        if ($slug === null) {
            $this->markTestSkipped('No tag found — create a post with a tag.');
        }

        $this->assertTemplateIs('/tag/' . $slug . '/', 'archive', 200);
    }

    // ─── Author archive ─────────────────────────────────────

    /**
     * An author archive URL must resolve to author.blade.php.
     *
     * WordPress hierarchy: author-{nicename} → author-{id} → author → archive → index.
     */
    public function test_author_archive_renders_author_template(): void
    {
        $nicename = DB::table('users')->value('user_nicename');

        if ($nicename === null) {
            $this->markTestSkipped('No user found.');
        }

        $this->assertTemplateIs('/author/' . $nicename . '/', 'author', 200);
    }

    /**
     * The author page must NOT render the generic archive template.
     */
    public function test_author_archive_does_not_render_archive_template(): void
    {
        $nicename = DB::table('users')->value('user_nicename');

        if ($nicename === null) {
            $this->markTestSkipped('No user found.');
        }

        $this->assertTemplateIsNot('/author/' . $nicename . '/', 'archive');
    }

    // ─── Search ──────────────────────────────────────────────

    /**
     * A search URL must resolve to search.blade.php.
     *
     * WordPress hierarchy: search → index.
     */
    public function test_search_renders_search_template(): void
    {
        $this->assertTemplateIs('/?s=test', 'search', 200);
    }

    /**
     * The search page must contain the search query term.
     */
    public function test_search_page_contains_query(): void
    {
        $this->assertResponseContains('/?s=pollora', 'pollora');
    }

    /**
     * Search must NOT render the home template even though it's on /.
     */
    public function test_search_does_not_render_home_template(): void
    {
        $this->assertTemplateIsNot('/?s=test', 'home');
    }

    // ─── Custom post type: single project ────────────────────

    /**
     * A single project URL must resolve to single-project.blade.php.
     *
     * WordPress hierarchy: single-{post_type}-{slug} → single-{post_type} → single → singular → index.
     * We have single-project.blade.php so it must match before falling to single.blade.php.
     */
    public function test_single_project_renders_single_project_template(): void
    {
        $slug = $this->getFirstPublishedProjectSlug();

        if ($slug === null) {
            $this->markTestSkipped('No published project found — run: wp starter-seed create');
        }

        $this->assertTemplateIs('/project/' . $slug . '/', 'single-project', 200);
    }

    /**
     * A single project must NOT render the generic single (post) template.
     */
    public function test_single_project_does_not_render_single_template(): void
    {
        $slug = $this->getFirstPublishedProjectSlug();

        if ($slug === null) {
            $this->markTestSkipped('No published project found.');
        }

        $this->assertTemplateIsNot('/project/' . $slug . '/', 'single');
    }

    // ─── Custom taxonomy: project_category ──────────────────

    /**
     * A custom taxonomy archive should resolve to taxonomy.blade.php.
     *
     * WordPress hierarchy: taxonomy-{taxonomy}-{term} → taxonomy-{taxonomy} → taxonomy → archive → index.
     */
    public function test_custom_taxonomy_renders_taxonomy_template(): void
    {
        $term = $this->getFirstProjectCategorySlug();

        if ($term === null) {
            $this->markTestSkipped('No project-category term found — create one with assigned projects.');
        }

        $this->assertTemplateIs('/project-category/' . $term . '/', 'taxonomy', 200);
    }

    // ─── Date archive (falls to archive.blade.php) ──────────

    /**
     * A date archive URL should resolve to archive.blade.php (no date-specific template).
     *
     * WordPress hierarchy: date → archive → index.
     */
    public function test_date_archive_renders_archive_template(): void
    {
        $year = date('Y');

        $this->assertTemplateIs('/' . $year . '/', 'archive', 200);
    }

    // ─── Priority: explicit Route::wp() wins over hierarchy ──

    /**
     * The homepage is handled by Route::wp('home'), not by the template hierarchy.
     * This verifies that explicit routes take priority over fallback.
     */
    public function test_explicit_route_takes_priority_over_hierarchy(): void
    {
        // Homepage has both Route::wp('home') and would match is_home in hierarchy.
        // The explicit route must win, rendering home.blade.php with its marker.
        $this->assertTemplateIs('/', 'home', 200);
    }

    // ─── Helpers ─────────────────────────────────────────────

    private function getFirstCategorySlug(): ?string
    {
        return DB::table('terms')
            ->join('term_taxonomy', 'terms.term_id', '=', 'term_taxonomy.term_id')
            ->where('term_taxonomy.taxonomy', 'category')
            ->where('term_taxonomy.count', '>', 0)
            ->value('terms.slug');
    }

    private function getFirstTagSlug(): ?string
    {
        return DB::table('terms')
            ->join('term_taxonomy', 'terms.term_id', '=', 'term_taxonomy.term_id')
            ->where('term_taxonomy.taxonomy', 'post_tag')
            ->where('term_taxonomy.count', '>', 0)
            ->value('terms.slug');
    }

    private function getFirstPublishedProjectSlug(): ?string
    {
        return DB::table('posts')
            ->where('post_type', 'project')
            ->where('post_status', 'publish')
            ->value('post_name');
    }

    private function getFirstProjectCategorySlug(): ?string
    {
        return DB::table('terms')
            ->join('term_taxonomy', 'terms.term_id', '=', 'term_taxonomy.term_id')
            ->where('term_taxonomy.taxonomy', 'project-category')
            ->where('term_taxonomy.count', '>', 0)
            ->value('terms.slug');
    }
}
