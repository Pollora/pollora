# Contributing to the Pollora skeleton

Thank you for taking the time to contribute. This guide answers the three
questions a first contribution actually runs into: **which repository**,
**which branch**, and **how do I know my change works**.

## Am I in the right repository?

This repository is the **skeleton** — the project template
`composer create-project pollora/pollora` installs. It holds the application
shell, the install path, and the test suites that prove a real site comes up.

Most behaviour lives elsewhere. Pick the repository that owns what you want to
change:

| You want to change | Repository |
|---|---|
| Routing, hooks, attributes, discovery, themes API — the framework itself | [Pollora/framework](https://github.com/Pollora/framework) |
| The application skeleton, the installer, the install and HTTP test suites | **this one** |
| The documentation published on [pollora.dev](https://pollora.dev) | [Pollora/documentation](https://github.com/Pollora/documentation) — never edit the website repository, it is overwritten on sync |
| The default theme | [Pollora/theme-default](https://github.com/Pollora/theme-default) |

If you are unsure, open an issue here and we will route it. A misfiled issue
costs nobody anything.

## Which branch?

**Branch from `main`, and target `main`.** This repository has no `develop`
branch: releases are cut on `release/*` branches and merged into `main`, which
is always the latest published skeleton.

> Note for anyone coming from [Pollora/framework](https://github.com/Pollora/framework):
> that repository *does* use Gitflow and you branch from `develop` there. The
> two differ on purpose — the framework is installed as a dependency, so it
> needs a pre-release line others can require as `dev-develop`. The skeleton
> has no such consumer.

Name your branch for what it does:

- `feature/short_description` — new behaviour
- `hotfix/short_description` — bug fix
- `support/short_description` — maintenance, tooling, docs
- `release/version_number` — maintainers only

Commit messages follow the [conventional commit](https://www.conventionalcommits.org)
format (`fix: `, `feat: `, `docs: `, `build: `, `test: `…). It is not enforced by
CI here, but it is what the history uses and what the changelog is written from.

## Setting up

The quickest path is [DDEV](https://ddev.readthedocs.io), which is also what CI
runs:

```bash
git clone git@github.com:<your-fork>/pollora.git
cd pollora
ddev start
ddev composer install
ddev exec php artisan pollora:install
```

Without DDEV: PHP 8.3+, Composer 2, Node 20+, and a database. `composer setup`
runs the whole sequence.

See [CLAUDE.md](CLAUDE.md) for the fuller development setup.

## Checking your change

There are three suites, and they answer different questions.

```bash
ddev composer test              # the Laravel test suite
ddev composer test:integration  # ~67 HTTP checks against the running site
ddev composer test:install <web|no-theme|decoy|artisan|checks>
```

`test:integration` and `test:install` read the site URL from
`POLLORA_TEST_URL`, as CI does:

```bash
ddev exec bash -c 'POLLORA_TEST_URL=https://pollora.ddev.site composer test:integration'
```

> **Every install scenario but `checks` drops the database.** They exist to
> install a site from nothing, so they start from nothing. That is why they
> refuse to run until you confirm the site is disposable with
> `POLLORA_INSTALL_TESTS=1` — run them on a throwaway site, never on one you
> are working in. `test:install checks` is the safe one: it asserts against a
> site as it stands and installs nothing.

```bash
ddev exec bash -c '
  POLLORA_INSTALL_TESTS=1 \
  POLLORA_TEST_URL=https://pollora.ddev.site \
  composer test:install web
'
```

The install scenarios are the ones worth knowing about. Most of what breaks in a
skeleton breaks at **install time**, on a path no unit test walks — four of the
six fixes in v13.32.0-beta.3 came from the WordPress web installer. Each
scenario installs a site its own way and then asserts against the result: `web`
through the wizard, `no-theme` with `themes/` empty, `decoy` against a URL that
already answers, `artisan` through `pollora:install`.

Run Laravel Pint before committing:

```bash
ddev exec vendor/bin/pint
```

CI does not check code style here — it checks that a site installs and answers.
Pint keeps the diff readable anyway.

## Opening the pull request

1. Push your branch to your fork and open a PR against `main`.
2. Say **how to test it**: the command, the page, or the scenario that shows the
   change working. This is the single most useful thing in a PR description.
3. CI runs the full matrix — every install scenario against both the locked
   framework and its `develop` branch. It takes about five minutes.

A red `Install (locked)` means a new project would break. A red
`Install (develop)` means the *next* framework release would break it — worth
reporting even if your change is unrelated.

## Reporting a bug

Open an issue using one of the templates. The details that actually shorten a
bug hunt:

- **Steps to reproduce**, ideally from a fresh `create-project`
- **What you expected** and **what happened**
- **Versions**: the output of `composer show pollora/framework` and
  `composer show pollora/pollora`, plus PHP and WordPress versions
- Whether `wp-login.php` is affected too — if it is, the cause is in the boot,
  not in the theme or the template

## Support

Questions and issues: open an issue on GitHub, or write to `dev@amphibee.fr`.
