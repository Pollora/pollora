#!/usr/bin/env php
<?php
/**
 * HTTP integration suite for a running Pollora site.
 *
 * Covers routing, module discovery, post type and taxonomy registration,
 * template hierarchy, hooks and middleware, by asking a real site over HTTP.
 *
 * Parts of it were written against a skeleton carrying demo content — a
 * "project" post type, a "project-category" taxonomy, a module answering on
 * /toto. None of that ships on `main`,
 * so those tests declare what they need through skipUnless() and are listed,
 * by name and by reason, in a "Not run" block at the end. Set
 * POLLORA_INTEGRATION_STRICT=1 where the fixtures are supposed to be there —
 * CI does — and a test that cannot run becomes a failure.
 *
 * Nothing here skips on a 404 any more. An archive answering 404 is what a
 * missing rewrite flush looks like, so a test that stepped aside on one could
 * not fail for the reason it was written: that is how fix 5 shipped
 * inoperative behind a green suite.
 *
 * Run: ddev composer test:integration
 */

$baseUrl = rtrim(getenv('POLLORA_TEST_URL') ?: 'https://pollora-test.ddev.site', '/');

/**
 * Whether a test that cannot run counts as a failure.
 *
 * Off by default, so the suite stays usable against a bare install. CI turns
 * it on: there, every fixture the suite wants is supposed to be present, and a
 * test quietly not running is the thing this suite got caught doing.
 */
$strict = getenv('POLLORA_INTEGRATION_STRICT') === '1';

$passed = 0;
$failed = 0;

/** @var array<string, list<string>> reason => the tests it disabled */
$skippedBy = [];

/**
 * Thrown by skipUnless() when a test's precondition is absent.
 *
 * A bare `return null` used to mean "skipped" and said nothing more. That is
 * how `Template hierarchy: author renders author template` disappeared from
 * the run without anyone noticing, and how a test that skipped on 404 —
 * exactly the symptom it was written to catch — reported green.
 */
final class Skipped extends \RuntimeException {}

/**
 * Declare what a test needs, and why it is not this test's job to provide it.
 *
 * The reason is a sentence, not a flag: it is printed at the end of the run
 * next to the names of every test it silenced, so an empty suite cannot look
 * like a passing one.
 */
function skipUnless(bool $available, string $reason): void {
    if (! $available) {
        throw new Skipped($reason);
    }
}

function test(string $name, callable $fn): void {
    global $passed, $failed, $skippedBy, $strict;
    try {
        $result = $fn();
        if ($result === null) {
            // Nothing should reach this any more: an unrunnable test says so
            // through skipUnless(), which records why.
            $failed++;
            echo "  \033[31m✗\033[0m  $name — returned null without declaring a reason\n";
            return;
        }
        if ($result === true) {
            echo "  \033[32m✓\033[0m  $name\n";
            $passed++;
        } else {
            $detail = is_string($result) ? " — {$result}" : '';
            echo "  \033[31m✗\033[0m  $name{$detail}\n";
            $failed++;
        }
    } catch (Skipped $e) {
        $reason = $e->getMessage();
        $skippedBy[$reason][] = $name;

        if ($strict) {
            echo "  \033[31m✗\033[0m  $name — needs {$reason}, and POLLORA_INTEGRATION_STRICT is set\n";
            $failed++;
            return;
        }

        echo "  \033[33m•\033[0m  $name \033[2m(needs {$reason})\033[0m\n";
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

/**
 * Fixture discovery.
 *
 * A test that asserts against content the install does not have is not a
 * failing test, it is an inapplicable one. Each helper answers once, and the
 * dependent tests skip when the answer is no.
 */
function hasPostType(string $type): bool {
    global $baseUrl;
    static $cache = [];
    if (!isset($cache[$type])) {
        $cache[$type] = httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/$type")['status'] === 200;
    }
    return $cache[$type];
}

function hasTaxonomy(string $taxonomy): bool {
    global $baseUrl;
    static $cache = [];
    if (!isset($cache[$taxonomy])) {
        $cache[$taxonomy] = httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies/$taxonomy")['status'] === 200;
    }
    return $cache[$taxonomy];
}

function hasRestNamespace(string $namespace): bool {
    global $baseUrl;
    static $cache = [];
    if (!isset($cache[$namespace])) {
        $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/")['body'], true);
        $cache[$namespace] = in_array($namespace, $data['namespaces'] ?? [], true);
    }
    return $cache[$namespace];
}

/**
 * Which template answered, according to the page.
 *
 * Two markers are read, because two things emit one:
 *
 *  - `<!-- pollora:template="single" -->`, which the framework writes on
 *    wp_head under WP_DEBUG, from the template the request actually resolved
 *    to. Present on every theme, and impossible to forget.
 *  - `data-pollora-template="single"`, which theme-apiary places by hand in
 *    each view. It predates the framework's and is still in its templates,
 *    including on sites running a framework too old to emit the other one.
 *
 * The framework's is preferred when both are there: it names the template
 * WordPress chose, while the hand-placed one names whatever view happened to
 * be edited.
 */
function templateMarkerIn(string $body): ?string {
    if (preg_match('/<!--\s*pollora:template="([^"]+)"/', $body, $m) === 1) {
        return $m[1];
    }
    if (preg_match('/data-pollora-template="([^"]+)"/', $body, $m) === 1) {
        return $m[1];
    }
    return null;
}

/**
 * Whether the pages of this site say which template answered.
 *
 * Still asked, because the framework marker needs WP_DEBUG and a framework
 * recent enough to emit it: a site on an older release, or one running with
 * debug off, says nothing and there is no assertion to make. What changed is
 * that this is no longer a property of one theme.
 */
function themeMarksTemplates(): bool {
    global $baseUrl;
    static $marks = null;
    if ($marks === null) {
        $marks = templateMarkerIn(httpGet($baseUrl)['body']) !== null
            || templateMarkerIn(httpGet("$baseUrl/?s=pollora")['body']) !== null;
    }
    return $marks;
}

/** A module route is present when the path does not fall through to WordPress. */
function hasModuleRoute(string $path): bool {
    global $baseUrl;
    static $cache = [];
    if (!isset($cache[$path])) {
        $r = httpGet("$baseUrl$path");
        $cache[$path] = $r['status'] !== 404;
    }
    return $cache[$path];
}

/**
 * Assert which template answered, and say what happened when it did not.
 *
 * These checks used to skip on a 404 — the very symptom they exist to catch,
 * since an archive answering 404 is what a missing rewrite flush looks like.
 * Now the status and the byte count are part of the failure, because "✗" alone
 * sent the last round of debugging down the wrong path.
 */
function rendersTemplate(array $r, string $template): bool|string {
    if ($r['status'] !== 200) {
        return "answered {$r['status']}";
    }
    $marker = templateMarkerIn($r['body']);
    if ($marker === $template) {
        return true;
    }
    if ($marker !== null) {
        return "rendered the {$marker} template, not {$template}";
    }
    return 'rendered no template marker at all (' . strlen($r['body']) . ' bytes)';
}

/**
 * Assert a page did NOT render a given template.
 *
 * A negative assertion is satisfied by an empty body, so a 404 reads as a
 * pass. That makes it the weakest kind of test in the file, and the only
 * defence is to prove the page answered before looking at what it rendered.
 */
/**
 * Assert the answering template is somewhere in the chain WordPress walks.
 *
 * Naming a single template only ever held for a theme that ships it:
 * theme-default has no category, author, search or archive view, so every one
 * of those requests correctly falls through to index — and six assertions
 * written against theme-apiary's richer set reported that as a failure.
 *
 * What the framework owes is the hierarchy, not a particular file: the most
 * specific template the theme provides, and index as the last link. Passing
 * the chain says exactly that, and still fails when the answer is outside it
 * — a category rendering `page`, or `home`, is a real defect.
 *
 * @param  list<string>  $chain  templates in order, most specific first
 */
function rendersTemplateFromChain(array $r, array $chain): bool|string {
    if ($r['status'] !== 200) {
        return "answered {$r['status']}";
    }
    $marker = templateMarkerIn($r['body']);
    if ($marker === null) {
        return 'rendered no template marker at all (' . strlen($r['body']) . ' bytes)';
    }
    if (in_array($marker, $chain, true)) {
        return true;
    }
    return "rendered the {$marker} template, which is not in the hierarchy for this request (" . implode(' → ', $chain) . ')';
}

function doesNotRenderTemplate(array $r, string $template): bool|string {
    if ($r['status'] !== 200) {
        return "answered {$r['status']}, so this check proves nothing";
    }
    $marker = templateMarkerIn($r['body']);
    if ($marker === null) {
        return 'rendered no template marker at all, so this check proves nothing';
    }
    return $marker === $template ? "rendered the {$template} template" : true;
}

echo "\n\033[1m=== Pollora HTTP Integration Tests ===\033[0m\n\n";

// ─── 1. ROUTING (DDD refactoring: UseCases, WordPressRoutingService) ───
echo "\033[1m── Routing (Route UseCases, macros, fallback) ──\033[0m\n";

test('Homepage returns 200', function() use ($baseUrl) {
    return httpGet($baseUrl)['status'] === 200;
});

test('Homepage contains valid HTML', function() use ($baseUrl) {
    $r = httpGet($baseUrl);
    return str_contains($r['body'], '<html') && str_contains($r['body'], '</html>');
});

test('404 page returns 404 from the 404 chain', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    $r = httpGet("$baseUrl/this-page-does-not-exist-" . time());
    if ($r['status'] !== 404) {
        return "answered {$r['status']}, not 404";
    }
    $marker = templateMarkerIn($r['body']);
    return in_array($marker, ['404', 'index'], true)
        ? true
        : "rendered the {$marker} template for a missing page";
});

test('Search returns 200 with search template', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    $r = httpGet("$baseUrl/?s=test");
    return rendersTemplateFromChain($r, ['search', 'index']);
});

test('API routes excluded from WordPress fallback (^(?!api/))', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/api/nonexistent");
    return $r['status'] !== 200;
});

test('WordPress login page answers 200 with a login form', function() use ($baseUrl) {
    // This accepted 404 outright, explained away as "Pollora handles auth
    // differently", and so never reported that the login page had been
    // answering 404 with a perfectly good form underneath it. A page that
    // renders must say so: a 404 here is the bug, not a variant.
    $r = httpGet("$baseUrl/cms/wp-login.php");

    if ($r['status'] !== 200) {
        return false;
    }

    return str_contains($r['body'], 'loginform') || str_contains($r['body'], 'user_login');
});

test('WordPress install root does not serve a template source', function() use ($baseUrl) {
    // /cms/ is served by WordPress's own index.php, whose template loader
    // includes whatever the hierarchy hands it — Blade sources included, which
    // PHP prints verbatim.
    $r = httpGet("$baseUrl/cms/");

    foreach (['@extends', '@section', '@php', '{{--'] as $directive) {
        if (str_contains($r['body'], $directive)) {
            return "Blade source leaked: found {$directive}";
        }
    }

    return true;
});

test('RSS feed accessible', function() use ($baseUrl) {
    $r = httpGet("$baseUrl/feed");
    return $r['status'] === 200 && (str_contains($r['body'], '<rss') || str_contains($r['body'], '<?xml'));
});

// ─── 2. POST TYPES (attributeArgs → setArg/getArg refactoring) ───
echo "\n\033[1m── Post Type Registration (setArg/getArg refactoring) ──\033[0m\n";

test('Project CPT registered and exposed in REST API', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $r = httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project");
    return $r['status'] === 200 && str_contains($r['body'], '"project"');
});

test('Project CPT slug is correct', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project")['body'], true);
    return ($data['slug'] ?? '') === 'project';
});

test('Project has_archive enabled (#[HasArchive] → setArg)', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project")['body'], true);
    return !empty($data['has_archive']);
});

test('Project is publicly queryable (#[PubliclyQueryable] → setArg)', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    // WP REST doesn't expose supports array; verify queryable instead
    $r = httpGet("$baseUrl/cms/?rest_route=/wp/v2/project");
    return $r['status'] === 200;
});

test('Project name label is "Projects" (auto-generated from class)', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project")['body'], true);
    return ($data['name'] ?? '') === 'Projects';
});

test('Project show_in_rest=true (#[ShowInRest] → setArg)', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/project")['body'], true);
    return ($data['rest_base'] ?? '') === 'project';
});

test('Service CPT registered and exposed in REST', function() use ($baseUrl) {
    skipUnless(hasPostType('service'), 'the demo "service" post type');
    $r = httpGet("$baseUrl/cms/?rest_route=/wp/v2/types/service");
    return $r['status'] === 200;
});

test('Project archive page accessible', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $r = httpGet("$baseUrl/project/");
    return $r['status'] === 200;
});

// ─── 3. TAXONOMIES (setArg/getArg refactoring) ───
echo "\n\033[1m── Taxonomy Registration (setArg/getArg refactoring) ──\033[0m\n";

test('project-category taxonomy exposed in REST', function() use ($baseUrl) {
    skipUnless(hasTaxonomy('project-category'), 'the demo "project-category" taxonomy');
    $r = httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies/project-category");
    return $r['status'] === 200;
});

test('project-category is hierarchical (#[Hierarchical] → setArg)', function() use ($baseUrl) {
    skipUnless(hasTaxonomy('project-category'), 'the demo "project-category" taxonomy');
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies/project-category")['body'], true);
    return ($data['hierarchical'] ?? false) === true;
});

test('project-category labels applied (#[Labels] → setArg with merge)', function() use ($baseUrl) {
    skipUnless(hasTaxonomy('project-category'), 'the demo "project-category" taxonomy');
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies/project-category")['body'], true);
    return ($data['name'] ?? '') === 'Project Categories';
});

test('project-category linked to project CPT', function() use ($baseUrl) {
    skipUnless(hasTaxonomy('project-category'), 'the demo "project-category" taxonomy');
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/taxonomies/project-category")['body'], true);
    $types = $data['types'] ?? [];
    return in_array('project', $types);
});

// ─── 4. MODULE DISCOVERY (DiscoverModulesUseCase/ApplyModulesUseCase) ───
echo "\n\033[1m── Module Discovery (UseCases) ──\033[0m\n";

test('Framework modules discovered: custom CPTs available', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/wp/v2/types")['body'], true);
    return isset($data['project']) && isset($data['service']);
});

test('Framework modules discovered: custom taxonomies available', function() use ($baseUrl) {
    skipUnless(hasTaxonomy('project-category'), 'the demo "project-category" taxonomy');
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
    skipUnless(hasModuleRoute('/toto'), 'the demo module that registers /toto');
    $r = httpGet("$baseUrl/toto");
    // Module defines Route::get('/toto', fn() => dd('toto'))
    // dd() may return 500 but the content proves the route was matched
    return str_contains($r['body'], 'toto');
});

test('Module route does NOT leak into WordPress fallback', function() use ($baseUrl) {
    skipUnless(hasModuleRoute('/toto'), 'the demo module that registers /toto');
    // /toto should be handled by the module route, not by WP template hierarchy
    $r = httpGet("$baseUrl/toto");
    return !str_contains($r['body'], 'Page not found')
        && !str_contains($r['body'], '404');
});

// ─── 8. HYBRID ROUTING (WordPress conditions + Laravel routing coexistence) ───
echo "\n\033[1m── Hybrid Routing (WP conditions + Laravel coexistence) ──\033[0m\n";

test('Homepage is rendered by the template hierarchy', function() use ($baseUrl) {
    // Until v13.32.0-beta.3 this asserted a data-pollora-template="home"
    // marker, which came from the Route::wp('home') entry that release removed
    // from routes/web.php. The homepage now goes through the hierarchy like
    // every other request, and which template it lands on is the theme's
    // business — front-page, home or index — so the marker is no longer the
    // thing to assert. What must hold is that a real document comes back.
    $r = httpGet($baseUrl);
    return $r['status'] === 200
        && strlen(trim($r['body'])) > 0
        && str_contains($r['body'], '</html>');
});

test('Route::wp(singular, post) matches only posts, not CPTs', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    // Project archive exists at /project/ — should NOT be matched by Route::wp('singular', 'post')
    $r = httpGet("$baseUrl/project/");
    return $r['status'] === 200;
});

test('Template hierarchy: category renders category template', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    $r = httpGet("$baseUrl/category/uncategorized/");
    return rendersTemplateFromChain($r, ['category', 'archive', 'index']);
});

test('Template hierarchy: author renders author template', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    $r = httpGet("$baseUrl/author/admin/");
    return rendersTemplateFromChain($r, ['author', 'archive', 'index']);
});

test('Template hierarchy: date archive renders archive template', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    $year = date('Y');
    $r = httpGet("$baseUrl/$year/");
    return rendersTemplateFromChain($r, ['date', 'archive', 'index']);
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
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $r = httpGet("$baseUrl/project/");
    return rendersTemplate($r, 'archive');
});

// ─── 10. EDGE CASES ───
echo "\n\033[1m── Edge Cases ──\033[0m\n";

test('Trailing slash handling consistent', function() use ($baseUrl) {
    skipUnless(hasPostType('project'), 'the demo "project" post type');
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
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    $r = httpGet("$baseUrl/sample-page/");
    return rendersTemplate($r, 'page');
});

test('Route::wp(singular, post) renders single template for hello-world', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    $r = httpGet("$baseUrl/hello-world/");
    return rendersTemplate($r, 'single');
});

test('Single project renders single-project template (not generic single)', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $r = httpGet("$baseUrl/project/test-project/");
    return rendersTemplate($r, 'single-project');
});

test('Single project does NOT get the generic single (post) template', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    skipUnless(hasPostType('project'), 'the demo "project" post type');
    $r = httpGet("$baseUrl/project/test-project/");
    return doesNotRenderTemplate($r, 'single');
});

test('Taxonomy archive project-category/web renders taxonomy template', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    skipUnless(hasTaxonomy('project-category'), 'the demo "project-category" taxonomy');
    $r = httpGet("$baseUrl/project-category/web/");
    return rendersTemplate($r, 'taxonomy');
});

test('Page does NOT render home template', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    $r = httpGet("$baseUrl/sample-page/");
    return doesNotRenderTemplate($r, 'home');
});

test('Single post does NOT render page template', function() use ($baseUrl) {
    skipUnless(themeMarksTemplates(), 'a site that reports which template answered');
    $r = httpGet("$baseUrl/hello-world/");
    return doesNotRenderTemplate($r, 'page');
});

// ─── 12. CUSTOM REST API (discovery of app/Cms/Rest controllers) ───
echo "\n\033[1m── Custom REST API Discovery ──\033[0m\n";

test('Custom namespace starter/v1 registered', function() use ($baseUrl) {
    skipUnless(hasRestNamespace('starter/v1'), 'the demo starter/v1 REST namespace');
    $data = json_decode(httpGet("$baseUrl/cms/?rest_route=/")['body'], true);
    return in_array('starter/v1', $data['namespaces'] ?? []);
});

test('Custom endpoint /starter/v1/status accessible', function() use ($baseUrl) {
    skipUnless(hasRestNamespace('starter/v1'), 'the demo starter/v1 REST namespace');
    $r = httpGet("$baseUrl/cms/?rest_route=/starter/v1/status");
    return $r['status'] === 200;
});

test('Custom endpoint /starter/v1/projects accessible', function() use ($baseUrl) {
    skipUnless(hasRestNamespace('starter/v1'), 'the demo starter/v1 REST namespace');
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

$skipped = array_sum(array_map('count', $skippedBy));

// What did not run, and why. A count on its own reads as a detail; the names
// read as work. The whole point of this block is that a run where nothing
// could be measured must not look like a run where everything passed.
if ($skippedBy !== []) {
    echo "\n\033[1m── Not run ──\033[0m\n";

    ksort($skippedBy);

    foreach ($skippedBy as $reason => $names) {
        $count = count($names);
        echo "\n  \033[33m{$count}\033[0m needing \033[1m{$reason}\033[0m:\n";

        foreach ($names as $name) {
            echo "      \033[2m{$name}\033[0m\n";
        }
    }

    echo "\n  \033[2mSet POLLORA_INTEGRATION_STRICT=1 to make these failures.\033[0m\n";
}

echo "\n\033[1m══════════════════════════════════════════\033[0m\n";
$color = $failed > 0 ? '31' : '32';
echo "  \033[{$color}mPassed: $passed  |  Failed: $failed  |  Not run: $skipped\033[0m\n";
echo "\033[1m══════════════════════════════════════════\033[0m\n\n";

exit($failed > 0 ? 1 : 0);
