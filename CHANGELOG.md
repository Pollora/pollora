# Changelog

All notable changes to the Pollora skeleton will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/Pollora/pollora/compare/v13.32.0-beta.4...main)

## [v13.32.0-beta.4](https://github.com/Pollora/pollora/compare/v13.32.0-beta.3...v13.32.0-beta.4) - 2026-09-22

### Changed
- Locked on `pollora/framework` v13.32.0-beta.4. Two assertions of the HTTP integration suite had been failing on the locked framework since they were written: the login page answering 200 with a form, and the install root serving no template source. Both required a fix that only `develop` carried, so `Install (locked)` was red on `main` and every pull request inherited it — which is how a genuinely broken branch stops being distinguishable from a healthy one

### Added
- The install scenarios cover the three fixes that had none. Fix 1 is now reproduced rather than approximated: `APP_URL` is pointed at the decoy so `wp_install()` really fetches a server that already answers with a cookie — no DNS hijacking needed, since `WP_HOME` and `WP_SITEURL` are built from it. Fix 3 is pinned by both its mechanism and its symptom, measured together on a live site: reverting it flips the finder order and the category archive goes from 34 094 bytes to 0. Fix 4's second themes directory is pinned by putting a theme in `WP_CONTENT_DIR/themes` and checking it cannot displace the active one
- The integration suite reads the framework's template marker, so its fourteen template assertions run on any theme rather than only on one that annotates its own views. They assert the hierarchy chain — `category → archive → index` — instead of naming a single file, because theme-default ships none of those and correctly falls through

### Fixed
- The integration suite can fail. It had 48 silent skips, eleven of which stepped aside on a 404 — the very symptom those tests exist to catch, since an archive answering 404 is what a missing rewrite flush looks like. The 404 skips are gone; what still cannot run is listed by name under the reason that disabled it, and `POLLORA_INTEGRATION_STRICT=1` turns those into failures

## [v13.32.0-beta.3](https://github.com/Pollora/pollora/compare/v13.32.0-beta.2...v13.32.0-beta.3) - 2026-09-21

### Added
- Install integration scenarios in `tests/install`, run with `composer test:install <scenario>`. Four of the six fixes in v13.32.0-beta.3 came from the WordPress web installer, a path no test walked — and the first version of the rewrite-rules fix was inoperative while 1040 unit tests were green. Each scenario installs a site its own way and then asserts against it: `web` (the wizard, then page rendering, rewrite rules and theme resolution), `no-theme` (the wizard with `themes/` empty, which must answer 503 with instructions rather than 500), `decoy` (installing while the target URL already answers with a cookie) and `artisan` (the `pollora:install` baseline). `checks` runs the assertions against a site as it stands, without reinstalling
- A page is only counted as rendered when it answers with a document. The theme's `index.php` stub used to answer 200 with zero bytes — no error, no content, invisible to any status-code check — so every page type is fetched and an empty or truncated body fails
- `.ddev/docker-compose.decoy.yaml`, a small web server that answers with a `Set-Cookie`, reachable as `http://decoy`. Installing against a URL that already answers is what made the installer fatal on `WP_Http_Cookie`, and the cookie is what reproduces it
- CI runs each scenario against both the locked framework and its `develop` branch

### Changed
- Locks [`pollora/framework` v13.32.0-beta.3](https://github.com/Pollora/framework/releases/tag/v13.32.0-beta.3), and installs [`pollora/theme-default` v1.4.0](https://github.com/Pollora/theme-default/releases/tag/v1.4.0) — whose templates follow the WordPress hierarchy, which this release needs since `routes/web.php` no longer declares routes on the theme's behalf
- `tests/integration.php`, the HTTP suite, is now part of the repository and runs with `composer test:integration`; CI runs it after the install. It was written against a skeleton carrying demo content — `project` and `service` post types, a `project-category` taxonomy, a module route and a `starter/v1` REST namespace — none of which ships here, so the tests that need them now skip instead of failing. Its base URL comes from `POLLORA_TEST_URL`
- `routes/web.php` no longer declares WordPress routes. Its four `Route::wp()` entries — `home`, `single` → `post`, `page`, `404` — took priority over the template hierarchy, so a theme's own naming never applied: an article rendered whatever `view('post')` resolved to, and a theme naming its templates after the hierarchy (`single.blade.php`) was ignored. Anything they did not cover — categories, tags, authors, dates, search, custom post types — had no route at all and already fell through to the hierarchy, which is where every request belongs. `Route::wp()` stays available for requests that need controller logic, middleware or a named route

## [v13.32.0-beta.2](https://github.com/Pollora/pollora/compare/v13.32.0-beta...v13.32.0-beta.2) - 2026-09-17

### Added
- [pollora.dev](https://pollora.dev) as the project website and documentation, in `composer.json` (`homepage`, `support`) and the README

### Fixed
- A fresh install no longer answers 500: `.env.example` sets `CACHE_STORE=database`, but no migration created the `cache` table, so discovery and the theme's view composers failed on the first request. Adds Laravel's `cache` and `cache_locks` migration

### Changed
- Locks [`pollora/framework` v13.32.0-beta.2](https://github.com/Pollora/framework/releases/tag/v13.32.0-beta.2), which completes `pollora:install` without interaction, keeps the former command names as aliases and renders Gutenberg blocks from `resources/views/blocks` with Blade
- CI installs the skeleton from scratch under DDEV (PHP 8.4, MariaDB 10.11, Apache — the stack of the local test install) and runs the installation tests, on every push, pull request and nightly, against both the locked framework and its `develop` branch. The previous workflow only ran on `master` and `*.x` with PHP 8.1/8.2, so it never ran

## [v13.32.0-beta](https://github.com/Pollora/pollora/compare/v13.4.0...v13.32.0-beta) - 2026-09-17

The skeleton now follows Laravel's version numbers, like [`pollora/framework`](https://github.com/Pollora/framework/releases/tag/v13.32.0-beta).

### Added
- `LICENSE` file with the MIT terms `composer.json` already declared

### Changed
- Requires `pollora/framework` `^13.32@beta`, Laravel 13.32 and WordPress 7.1
- Composer scripts use the renamed `pollora:env:setup` command (was `pollora:env-setup`); `pollora:make-theme` is now `pollora:make:theme`
- Plugins and themes are installed from [wp-packages](https://repo.wp-packages.org) (`wp-plugin/*`, `wp-theme/*`) instead of wpackagist
- `composer dev` runs `php artisan dev`
- `Illuminate\Support\Carbon` used instead of `Carbon\Carbon`

### Removed
- The "Silence is golden" `index.php` from `public/content/plugins`

## [v13.4.0](https://github.com/Pollora/pollora/compare/v13.0.0...v13.4.0) - 2026-05-13

### Added
- Default theme redesign with Pollora branding (gradient hero, feature cards, latest posts)
- Menu fallback with published pages when no WordPress menu is configured
- `entry-content` CSS styles for WordPress blocks (blockquotes, links, headings, lists)
- Mobile hamburger menu with toggle
- `@query` directive for latest posts on homepage

### Changed
- Replaced `@loop`/`@endloop` with `@posts`/`@endposts` (Sage Directives)
- Replaced `Loop::` facade calls with Sage Directives (`@title`, `@content`, `@permalink`, `@published`)
- Links styled via `theme.json` (no underline by default, foreground color)
- Updated `theme.json` color palette to Pollora brand colors

### Fixed
- Blockquote/citation styling for WordPress `wp-block-quote` blocks
- Button hover states no longer overridden by WordPress global link styles (using `wp-element-button`)

## [v13.0.0](https://github.com/Pollora/pollora/compare/v12.0.0...v13.0.0) - 2026-04-20

### Added
- E2E installation tests (EnvironmentSetupTest, WordPressInstallTest, SiteHealthTest)
- `WordPressAssertions` trait for reusable WP database assertions
- WordPress hybrid routing (`Route::wp('home')`, `Route::wp('single')`, `Route::wp('page')`, `Route::wp('404')`)
- 404 error page with Pollora branding
- `composer.local.json` support in `.gitignore` for local development overrides

### Changed
- **BREAKING**: Upgraded to Laravel 13 (`laravel/framework ^13.5`)
- **BREAKING**: Requires `pollora/framework ^13.0`
- Upgraded to `laravel/tinker ^3.0`, `laravel/sanctum ^4.3 || ^5.0`
- Upgraded to Vite 7 and `laravel-vite-plugin ^2.0`
- Adopted PHP 8.3+ attributes for class properties (Laravel Shift)
- Added `composer run setup` and `composer run test` scripts
- Removed default Laravel welcome view in favor of WordPress routing
- Removed legacy `MyTheme`/`Pollora` theme providers

### Removed
- `resources/views/welcome.blade.php` (replaced by `Route::wp('home')`)
- `tests/Feature/ExampleTest.php` (replaced by Installation tests)

## [v12.0.0](https://github.com/Pollora/pollora/releases/tag/v12.0.0) - 2026-04-20

Initial tagged release of the Pollora skeleton targeting Laravel 12.

### Features
- Laravel 12 + WordPress 6.9 integration via Pollora Framework
- Default theme with Blade templates and Sage Directives
- DDEV development environment support
- Custom post types and taxonomies via config files
- Hybrid routing with `Route::wp()` macro
- Meta Box integration for custom fields and Gutenberg blocks