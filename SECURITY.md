# Security Policy

## What this repository covers

This is the **Pollora skeleton** — the project template
`composer create-project pollora/pollora` installs. Report here anything in the
application shell, the installer, or the shipped configuration.

Most code runs from the framework. A vulnerability in routing, hooks,
attributes, discovery or the themes API belongs to
[Pollora/framework](https://github.com/Pollora/framework/security/advisories/new).
If you are unsure which one it is, report it to either — we will route it. Do
not let the question delay the report.

## Supported versions

| Version | Supported |
|---------|-----------|
| 13.x    | :white_check_mark: |
| < 13.0  | :x: |

## Reporting a vulnerability

**Do not open a public issue.**

Use [GitHub Security Advisories](https://github.com/Pollora/pollora/security/advisories/new)
to report it privately. If that is not available to you, write to
`dev@amphibee.fr`.

You can expect:

- An acknowledgment within **48 hours**
- A status update within **7 days**
- A fix released as a patch version as soon as possible

Please include the versions involved — the output of
`composer show pollora/pollora` and `composer show pollora/framework` — and the
steps to reproduce.

Thank you for helping keep Pollora secure.
