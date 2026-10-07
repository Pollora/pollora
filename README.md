<p align="center">
  <a href="https://pollora.dev">
    <img src="https://raw.githubusercontent.com/Pollora/.github/main/brand/banners/pollora.png" width="100%" alt="Pollora: The Laravel framework for WordPress">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/pollora/pollora"><img src="https://img.shields.io/packagist/v/pollora/pollora" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/pollora/pollora"><img src="https://img.shields.io/packagist/dt/pollora/pollora" alt="Total Downloads"></a>
  <a href="https://github.com/Pollora/pollora/actions/workflows/tests.yml"><img src="https://github.com/Pollora/pollora/actions/workflows/tests.yml/badge.svg" alt="Tests"></a>
  <a href="LICENSE"><img src="https://img.shields.io/github/license/Pollora/pollora" alt="License"></a>
</p>

**Pollora is the Laravel framework for WordPress**, and this repository is the skeleton `composer create-project` installs. WordPress runs inside a Laravel application: the front end uses Laravel routing, controllers, Blade and Eloquent, while the WordPress admin, database and plugins keep working as usual. You start a WordPress site with a real Laravel project around it, instead of a theme folder full of `functions.php` code.

[Website](https://pollora.dev) · [Documentation](https://pollora.dev/getting-started/installation/) · [Why Pollora](https://pollora.dev/why/) · [How Pollora compares with Acorn, Sage, Radicle and Corcel](https://pollora.dev/compare/) · [1-minute tour](https://www.youtube.com/watch?v=Wk1VzPapqM8)

## Installation

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

Requirements:

- PHP 8.4+ (this skeleton's `composer.lock` ships Symfony 8, which requires it)
- Composer 2.x
- Node.js 20.19+ or 22.12+ & npm (the floor Vite 7 requires)
- A database (MySQL, MariaDB, or SQLite)
- WordPress 7.1+ is installed for you through Composer

## What you get

- **WordPress routing** with `Route::wp()` and template hierarchy support
- **PHP attributes** for hooks, post types, taxonomies, scheduling, and REST routes
- **Blade templates** with [Sage Directives](https://log1x.github.io/sage-directives-docs/) (`@title`, `@content`, `@permalink`...)
- **Eloquent ORM** with WordPress models via [Colt](https://github.com/Pollora/colt)
- **Multi-theme support** with parent/child themes and Vite asset bundling
- **Auto-discovery** for service providers, hooks, and components
- **Gutenberg** blocks rendered with Blade, block patterns and categories
- Laravel's [database migrations](https://laravel.com/docs/migrations), [sessions](https://laravel.com/docs/session), and [cache](https://laravel.com/docs/cache), next to WordPress

## Documentation

Full documentation is available at **[pollora.dev](https://pollora.dev)**, starting with the [installation guide](https://pollora.dev/getting-started/installation/).

## Changelog

All notable changes are documented in the [CHANGELOG](CHANGELOG.md).

## Support the project

Questions, ideas and feedback are welcome in [GitHub Discussions](https://github.com/Pollora/pollora/discussions).

## Contributing

Contributions are welcome: see the [contributing guide](CONTRIBUTING.md). Branch from `main`: this repository has no `develop` branch. Participation is covered by our [Code of Conduct](CODE_OF_CONDUCT.md). Report security issues privately, as described in the [security policy](SECURITY.md).

## License

Pollora is open-source software licensed under the [MIT license](LICENSE). © [RuBee group](https://rubee.group)
