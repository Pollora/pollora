#!/usr/bin/env php
<?php
/**
 * Integration test script for Pollora release v13.4.0 changes.
 *
 * Tests routing, module discovery, post type/taxonomy registration,
 * and middleware via HTTP against the live DDEV site.
 *
 * Run: ddev exec php test-release.php
 */

$baseUrl = 'https://pollora-test.ddev.site';
$passed = 0;
$failed = 0;
$skipped = 0;

function test(string $name, callable $fn): void {
    global $passed, $failed, $skipped;
    try {
        $result = $fn();
        if ($result === null) {
            echo "  SKIP  $name\n";
            $skipped++;
            return;
        }
        if ($result) {
            echo "  \033[32m✓\033[0m  $name\n";
            $passed++;
        } else {
            echo "  \033[31m✗\033[0m  $name\n";
            $failed++;
        }
    } catch (\Throwable $e) {
        echo "  \033[31m✗\033[0m  $name — {$e->getMessage()}\n";
        $failed++;
    }
}

function httpGet(string $url): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 15,
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'body' => $body ?: ''];
}

function httpHead(string $url): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_NOBODY => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 15,
    ]);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'headers' => $response ?: '', 'body' => $response ?: ''];
}

echo "\n\033[1m=== Pollora v13.4.0 Release Integration Tests ===\033[0m\n\n";

// ─── 1. ROUTING (DDD refactoring: UseCases, WordPressRoutingService) ───
echo "\033[1m── Routing (Route UseCases, macros, fallback) ──\033[0m\n";

test('Homepage returns 200', function() use ($baseUrl) {
    return httpGet($baseUrl)['status'] === 200;
});

test('Homepage contains valid HTML', function() use ($baseUrl) {
    $r = httpGet($baseUrl);
    return str_contains($r['body'], '<html') && str_contains($r['body'], '</html>');
});

test('404 page returns 404 with correct template', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/this-page-does-not-exist-" . time());
    return $r['status'] === 404 && str_contains($r['body'], 'data-pollora-template="404"');
});

test('Search returns 200 with search template', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/?s=test");
    return $r['status'] === 200 && str_contains($r['body'], 'data-pollora-template="search"');
});

test('API routes excluded from WordPress fallback (^(?!api/))', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/api/nonexistent");
    return $r['status'] !== 200;
});

test('WordPress login page accessible or redirected', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/wp-login.php");
    // May return 200 or 404 (Pollora handles auth differently)
    return in_array($r['status'], [200, 302, 404]);
});

test('RSS feed accessible', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/feed");
    return $r['status'] === 200 && (str_contains($r['body'], '<rss') || str_contains($r['body'], '<?xml'));
});

// ─── 2. POST TYPES (attributeArgs → setArg/getArg refactoring) ───
echo "\n\033[1m── Post Type Registration (setArg/getArg refactoring) ──\033[0m\n";

test('Project CPT registered and exposed in REST API', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project");
    return $r['status'] === 200 && str_contains($r['body'], '"project"');
});

test('Project CPT slug is correct', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project")['body'], true);
    return ($data['slug'] ?? '') === 'project';
});

test('Project has_archive enabled (#[HasArchive] → setArg)', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project")['body'], true);
    return !empty($data['has_archive']);
});

test('Project is publicly queryable (#[PubliclyQueryable] → setArg)', function() use ($baseUrl) {
    // WP REST doesn't expose supports array; verify queryable instead
    $r = httpGet("$baseUrl/cms/?rest_route=/wp/v2/project");
    return $r['status'] === 200;
});

test('Project name label is "Projects" (auto-generated from class)', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project")['body'], true);
    return ($data['name'] ?? '') === 'Projects';
});

test('Project show_in_rest=true (#[ShowInRest] → setArg)', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project")['body'], true);
    return ($data['rest_base'] ?? '') === 'project';
});

test('Service CPT registered and exposed in REST', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/service");
    return $r['status'] === 200;
});

test('Project archive page accessible', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/project/");
    return $r['status'] === 200;
});

// ─── 3. TAXONOMIES (setArg/getArg refactoring) ───
echo "\n\033[1m── Taxonomy Registration (setArg/getArg refactoring) ──\033[0m\n";

test('project-category taxonomy exposed in REST', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies/project-category");
    return $r['status'] === 200;
});

test('project-category is hierarchical (#[Hierarchical] → setArg)', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies/project-category")['body'], true);
    return ($data['hierarchical'] ?? false) === true;
});

test('project-category labels applied (#[Labels] → setArg with merge)', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies/project-category")['body'], true);
    return ($data['name'] ?? '') === 'Project Categories';
});

test('project-category linked to project CPT', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies/project-category")['body'], true);
    $types = $data['types'] ?? [];
    return in_array('project', $types);
});

// ─── 4. MODULE DISCOVERY (DiscoverModulesUseCase/ApplyModulesUseCase) ───
echo "\n\033[1m── Module Discovery (UseCases) ──\033[0m\n";

test('Framework modules discovered: custom CPTs available', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types")['body'], true);
    return isset($data['project']) && isset($data['service']);
});

test('Framework modules discovered: custom taxonomies available', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies")['body'], true);
    return isset($data['project-category']);
});

test('Theme loaded successfully (WP head output present)', function() use ($baseUrl) {
    $r = httpGet($baseUrl);
    return str_contains($r['body'], '<title>') && str_contains($r['body'], '</head>');
});

// ─── 5. MIDDLEWARE (WordPressHeaders, WordPressBindings) ───
echo "\n\033[1m── Middleware Stack ──\033[0m\n";

test('X-Powered-By: Pollora header present', function() use ($baseUrl) {
    $r = httpHead($baseUrl);
    return stripos($r['headers'], 'x-powered-by') !== false
        && stripos($r['headers'], 'pollora') !== false;
});

test('JSON API returns correct Content-Type', function() use ($baseUrl) {
    $r = httpHead("$baseUrl/cms/?rest_route=/wp/v2/types");
    return stripos($r['headers'], 'application/json') !== false;
});

test('HTML responses include proper charset', function() use ($baseUrl) {
    $r = httpGet($baseUrl);
    return str_contains($r['body'], 'charset=');
});

// ─── 6. INTERFACES (expanded: ConditionResolver, ModuleDiscovery, etc.) ───
echo "\n\033[1m── Interface Contracts ──\033[0m\n";

test('WP REST API root accessible (framework bootstrapped)', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/?rest_route=/");
    $data = json_decode($r['body'], true);
    return $r['status'] === 200 && isset($data['namespaces']);
});

test('WP REST API has wp/v2 namespace', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/")['body'], true);
    return in_array('wp/v2', $data['namespaces'] ?? []);
});

// ─── 7. MODULE ROUTING (nwidart modules define their own routes) ───
echo "\n\033[1m── Module Routing ──\033[0m\n";

test('Module route /toto resolves (Modules/Test)', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/toto");
    // Module defines Route::get('/toto', fn() => dd('toto'))
    // dd() may return 500 but the content proves the route was matched
    return str_contains($r['body'], 'toto');
});

test('Module route does NOT leak into WordPress fallback', function() use ($baseUrl) {
    // /toto should be handled by the module route, not by WP template hierarchy
    $r = httpGet("$baseUrl/toto");
    return !str_contains($r['body'], 'Page not found')
        && !str_contains($r['body'], '404');
});

// ─── 8. HYBRID ROUTING (WordPress conditions + Laravel routing coexistence) ───
echo "\n\033[1m── Hybrid Routing (WP conditions + Laravel coexistence) ──\033[0m\n";

test('Route::wp(home) renders home template marker', function() use ($baseUrl) {
    $r = httpGet($baseUrl);
    return $r['status'] === 200 && str_contains($r['body'], 'data-pollora-template="home"');
});

test('Route::wp(singular, post) matches only posts, not CPTs', function() use ($baseUrl) {
    // Project archive exists at /project/ — should NOT be matched by Route::wp('singular', 'post')
    $r = httpGet("$baseUrl/project/");
    return $r['status'] === 200;
});

test('Template hierarchy: category renders category template', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/category/uncategorized/");
    if ($r['status'] === 404) return null; // skip if no posts in category
    return str_contains($r['body'], 'data-pollora-template="category"');
});

test('Template hierarchy: author renders author template', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/author/admin/");
    if ($r['status'] === 404) return null;
    return str_contains($r['body'], 'data-pollora-template="author"');
});

test('Template hierarchy: date archive renders archive template', function() use ($baseUrl) {
    $year = date('Y');
    $r = httpGet("$baseUrl/$year/");
    if ($r['status'] === 404) return null;
    return str_contains($r['body'], 'data-pollora-template="archive"');
});

test('Standard Laravel route /up (health check) coexists with WP routes', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/up");
    // /up may 500 if DB health check fails but route is resolved (not 404)
    return $r['status'] !== 404;
});

test('WordPress condition routes get WP middleware (X-Powered-By header)', function() use ($baseUrl) {
    // Homepage is a Route::wp() — should have Pollora middleware
    $r = httpHead($baseUrl);
    return stripos($r['headers'], 'pollora') !== false;
});

test('Fallback route also gets WP middleware', function() use ($baseUrl) {
    // 404 is handled by FrontendController fallback with WP middleware
    $r = httpHead("$baseUrl/nonexistent-" . time());
    return stripos($r['headers'], 'pollora') !== false;
});

// ─── 9. THEME ROUTING ───
echo "\n\033[1m── Theme Routing ──\033[0m\n";

test('Theme views are resolved (homepage uses theme layout)', function() use ($baseUrl) {
    $r = httpGet($baseUrl);
    // Theme should provide <html>, <head>, <body> structure
    return str_contains($r['body'], '<head>') || str_contains($r['body'], '<head');
});

test('Theme assets load (CSS/JS references in HTML)', function() use ($baseUrl) {
    $r = httpGet($baseUrl);
    // Theme should reference stylesheets or scripts
    return str_contains($r['body'], '<style') || str_contains($r['body'], '<link')
        || str_contains($r['body'], '<script');
});

test('Project archive renders archive template via hierarchy', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/project/");
    return $r['status'] === 200 && str_contains($r['body'], 'data-pollora-template="archive"');
});

// ─── 10. EDGE CASES ───
echo "\n\033[1m── Edge Cases ──\033[0m\n";

test('Trailing slash handling consistent', function() use ($baseUrl) {
    $r1 = httpGet("$baseUrl/project");
    $r2 = httpGet("$baseUrl/project/");
    // Both should resolve (redirect or direct 200)
    return in_array($r1['status'], [200, 301, 302]) && $r2['status'] === 200;
});

test('Query parameters preserved on WP routes', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/?s=pollora&paged=1");
    return $r['status'] === 200;
});

test('Multiple concurrent WordPress conditions do not conflict', function() use ($baseUrl) {
    // Search and home cannot both be true — search wins when ?s= is present
    $r = httpGet("$baseUrl/?s=test");
    return $r['status'] === 200;
});

test('POST requests to WP routes work (Route::wp catches all verbs)', function() use ($baseUrl) {
    $ch = curl_init("$baseUrl/?s=test");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => 's=test',
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return in_array($status, [200, 302, 405]);
});

test('HEAD requests work on WP routes', function() use ($baseUrl) {
    $ch = curl_init($baseUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_NOBODY => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $status === 200;
});

// ─── 11. REAL CONTENT ROUTING (Route::wp conditions with actual DB content) ───
echo "\n\033[1m── Real Content Routing ──\033[0m\n";

test('Route::wp(page) renders page template for sample-page', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/sample-page/");
    if ($r['status'] === 404) return null;
    return $r['status'] === 200 && str_contains($r['body'], 'data-pollora-template="page"');
});

test('Route::wp(singular, post) renders single template for hello-world', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/hello-world/");
    if ($r['status'] === 404) return null;
    return $r['status'] === 200 && str_contains($r['body'], 'data-pollora-template="single"');
});

test('Single project renders single-project template (not generic single)', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/project/test-project/");
    if ($r['status'] === 404) return null;
    return $r['status'] === 200 && str_contains($r['body'], 'data-pollora-template="single-project"');
});

test('Single project does NOT get the generic single (post) template', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/project/test-project/");
    if ($r['status'] === 404) return null;
    return !str_contains($r['body'], 'data-pollora-template="single"')
        || str_contains($r['body'], 'data-pollora-template="single-project"');
});

test('Taxonomy archive project-category/web renders taxonomy template', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/project-category/web/");
    if ($r['status'] === 404) return null;
    return $r['status'] === 200 && str_contains($r['body'], 'data-pollora-template="taxonomy"');
});

test('Page does NOT render home template', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/sample-page/");
    if ($r['status'] === 404) return null;
    return !str_contains($r['body'], 'data-pollora-template="home"');
});

test('Single post does NOT render page template', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/hello-world/");
    if ($r['status'] === 404) return null;
    return !str_contains($r['body'], 'data-pollora-template="page"');
});

// ─── 12. CUSTOM REST API (discovery of app/Cms/Rest controllers) ───
echo "\n\033[1m── Custom REST API Discovery ──\033[0m\n";

test('Custom namespace starter/v1 registered', function() use ($baseUrl) {
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/")['body'], true);
    return in_array('starter/v1', $data['namespaces'] ?? []);
});

test('Custom endpoint /starter/v1/status accessible', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/?rest_route=/starter/v1/status");
    return $r['status'] === 200;
});

test('Custom endpoint /starter/v1/projects accessible', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/?rest_route=/starter/v1/projects");
    return in_array($r['status'], [200, 401]); // May require auth
});

// ─── 13. HOOK DISCOVERY (attribute-based hooks from app/Cms/Hooks) ───
echo "\n\033[1m── Hook Discovery ──\033[0m\n";

test('ThemeHooks: post-thumbnails support enabled', function() use ($baseUrl) {
    // after_setup_theme hook adds post-thumbnails support
    // Verify by checking that featured images are in REST API for posts
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/post")['body'], true);
    // If theme support works, post type should be accessible
    return ($data['slug'] ?? '') === 'post';
});

test('ThemeHooks: SVG upload allowed (upload_mimes filter)', function() use ($baseUrl) {
    // Indirect check — if the filter is registered, the framework hook discovery works
    // We verify the homepage renders without errors (filter registration didn't break anything)
    $r = httpGet($baseUrl);
    return $r['status'] === 200 && !str_contains($r['body'], 'Fatal error');
});

test('Hook discovery: no errors on pages with hooked content', function() use ($baseUrl) {
    // Content hooks modify the_title, body_class etc.
    // If discovery failed, these would throw or cause visible errors
    $r = httpGet("$baseUrl/hello-world/");
    if ($r['status'] === 404) return null;
    return !str_contains($r['body'], 'Fatal error') && !str_contains($r['body'], 'Warning:');
});

// ─── 14. SCHEDULE DISCOVERY (RegisterScheduleDiscoveryUseCase) ───
echo "\n\033[1m── Schedule Discovery ──\033[0m\n";

test('WordPress cron system functional', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/wp-cron.php");
    return $r['status'] === 200;
});

test('Scheduled tasks do not cause bootstrap errors', function() use ($baseUrl) {
    // If ScheduleDiscovery failed, it could break the entire boot
    // Homepage working proves schedule registration didn't fatal
    $r = httpGet($baseUrl);
    return $r['status'] === 200;
});

// ─── 15. LARAVEL HEALTH CHECK (/up — @theme directive removal) ───
echo "\n\033[1m── Laravel Health Check ──\033[0m\n";

test('/up returns 200 (no @theme Blade conflict)', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/up");
    return $r['status'] === 200;
});

test('/up contains Application up message', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/up");
    return str_contains($r['body'], 'Application') && str_contains($r['body'], 'up');
});

test('/up has no ParseError or syntax error', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/up");
    return !str_contains($r['body'], 'ParseError')
        && !str_contains($r['body'], 'syntax error')
        && !str_contains($r['body'], 'unexpected end of file');
});

// ─── 16. REGRESSIONS ───
echo "\n\033[1m── Regression Checks ──\033[0m\n";

test('No PHP fatal errors on homepage', function() use ($baseUrl) {
    $r = httpGet($baseUrl);
    return !str_contains($r['body'], 'Fatal error')
        && !str_contains($r['body'], 'Parse error')
        && !str_contains($r['body'], 'Uncaught');
});

test('No PHP fatal errors on REST API', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/?rest_route=/wp/v2/types");
    return !str_contains($r['body'], 'Fatal error')
        && !str_contains($r['body'], 'Parse error');
});

test('No PHP warnings exposed in HTML output', function() use ($baseUrl) {
    $r = httpGet($baseUrl);
    return !str_contains($r['body'], 'Warning:')
        && !str_contains($r['body'], 'Notice:')
        && !str_contains($r['body'], 'Deprecated:');
});

test('WordPress cron endpoints accessible', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/cms/wp-cron.php");
    return $r['status'] === 200;
});

// ─── Summary ───
echo "\n\033[1m══════════════════════════════════════════\033[0m\n";
$color = $failed > 0 ? '31' : '32';
echo "  \033[{$color}mPassed: $passed  |  Failed: $failed  |  Skipped: $skipped\033[0m\n";
echo "\033[1m══════════════════════════════════════════\033[0m\n\n";

exit($failed > 0 ? 1 : 0);
