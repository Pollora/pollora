<p align="center">
  <a href="https://pollora.dev">
    <img src="resources/images/pollora-logo.svg" width="400" alt="Pollora">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/pollora/pollora"><img src="https://img.shields.io/packagist/v/pollora/pollora?include_prereleases" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/pollora/pollora"><img src="https://img.shields.io/packagist/dt/pollora/pollora" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/pollora/pollora"><img src="https://img.shields.io/packagist/l/pollora/pollora" alt="License"></a>
</p>

## About Pollora

**Pollora is the Laravel framework for WordPress**, and this repository is the skeleton `composer create-project` installs. WordPress runs inside a Laravel application: the front end uses Laravel routing, controllers, Blade and Eloquent, while the WordPress admin, database and plugins keep working as usual.

[Website](https://pollora.dev) · [Documentation](https://pollora.dev/getting-started/installation/) · [Why Pollora](https://pollora.dev/why/) · [Pollora vs Acorn, Sage, Radicle and Corcel](https://pollora.dev/compare/) · [1-minute tour](https://www.youtube.com/watch?v=Wk1VzPapqM8)

What you get:

- **WordPress routing** with `Route::wp()` and template hierarchy support
- **PHP attributes** for hooks, post types, taxonomies, scheduling, and REST routes
- **Blade templates** with [Sage Directives](https://log1x.github.io/sage-directives-docs/) (`@title`, `@content`, `@permalink`...)
- **Eloquent ORM** with WordPress models via [Colt](https://github.com/Pollora/colt)
- **Multi-theme support** with parent/child themes and Vite asset bundling
- **Auto-discovery** for service providers, hooks, and components
- **Gutenberg** block pattern and category management
- Seamless [database migrations](https://laravel.com/docs/migrations), [sessions](https://laravel.com/docs/session), and [cache](https://laravel.com/docs/cache)

## Documentation

Full documentation is available at **[pollora.dev](https://pollora.dev)**, starting with the [installation guide](https://pollora.dev/getting-started/installation/).

## Quick Start

With the [Pollora CLI](https://github.com/Pollora/cli), which can also set up a [DDEV](https://ddev.readthedocs.io) environment:

```bash
composer global require pollora/cli
pollora new my-project --ddev
```

Or with Composer:

```bash
composer create-project pollora/pollora my-project
cd my-project
# Configure your .env with database credentials, then:
php artisan pollora:install
```

The [installation guide](https://pollora.dev/getting-started/installation/) covers both paths, the interactive setup and DDEV.

## Requirements

- PHP 8.4+ (this skeleton's `composer.lock` ships Symfony 8, which requires it)
- Composer 2.x
- Node.js 20.19+ or 22.12+ & npm (the floor Vite 7 requires)
- A database (MySQL, MariaDB, or SQLite)
- WordPress 7.1+ is installed for you through Composer

## Sponsors

We extend our heartfelt gratitude to our sponsors for supporting Pollora's development. If you're interested in sponsoring, contact [olivier@amphibee.fr](mailto:olivier@amphibee.fr).

## Changelog

All notable changes are documented in the [CHANGELOG](CHANGELOG.md).

## Contributing

Considering a contribution to Pollora? See the [contribution guide](CONTRIBUTING.md).
Branch from `main` — this repository has no `develop` branch. Participation is
covered by our [Code of Conduct](CODE_OF_CONDUCT.md).

## Security

If you discover a security vulnerability, please report it privately via [GitHub Security Advisories](https://github.com/Pollora/pollora/security/advisories/new) rather than opening an issue. See [SECURITY.md](SECURITY.md) for what to include and what to expect.

## License

Pollora is open-sourced software licensed under the [MIT license](LICENSE).
