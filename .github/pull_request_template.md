## What this changes

<!-- One or two sentences. What was wrong or missing, and what it does now. -->

## How to test it

<!--
The most useful part of this description. The command, the page, or the
scenario that shows the change working — enough for a reviewer to see it
rather than take your word for it.

  ddev exec bash -c 'POLLORA_TEST_URL=https://pollora.ddev.site composer test:integration'
-->

## Checklist

- [ ] Targets `main` (this repository has no `develop` branch)
- [ ] `ddev composer test` passes
- [ ] `ddev exec vendor/bin/pint` run on the changed files
- [ ] If it touches the install path: the relevant `composer test:install <scenario>` passes
- [ ] `CHANGELOG.md` updated under `[Unreleased]`, if the change is user-visible
