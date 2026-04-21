# Changelog

All notable changes to the Pollora skeleton will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/Pollora/pollora/compare/v13.0.0...main)

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