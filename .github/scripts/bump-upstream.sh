#!/usr/bin/env bash
#
# Moves WordPress and Laravel in composer.lock to the newest versions
# composer.json allows, and records it under [Unreleased] in CHANGELOG.md.
# Changes the working tree only; the workflow commits, pushes and opens the pull
# request. pollora/framework has its own workflow (bump-framework.sh).
#
# Prints, one per line, for $GITHUB_OUTPUT:
#   changed=false                              the lock is up to date
#   changed=true, wordpress=…, laravel=…       the lock moved

set -euo pipefail

locked_version() {
    jq -r --arg p "$1" '.packages[] | select(.name == $p) | .version' composer.lock | sed 's/^v//'
}

old_wordpress=$(locked_version johnpbloch/wordpress-core)
old_laravel=$(locked_version laravel/framework)

# --ignore-platform-req='ext-*' only: the PHP version set in composer.json
# (config.platform) must still hold, or the lock could require a newer PHP.
composer update johnpbloch/wordpress johnpbloch/wordpress-core laravel/framework \
    --with-dependencies --no-install --no-scripts --no-interaction --no-progress \
    --ignore-platform-req='ext-*' >&2

wordpress=$(locked_version johnpbloch/wordpress-core)
laravel=$(locked_version laravel/framework)

if [ "$old_wordpress" = "$wordpress" ] && [ "$old_laravel" = "$laravel" ]; then
    echo "changed=false"
    exit 0
fi

OLD_WORDPRESS="$old_wordpress" WORDPRESS="$wordpress" OLD_LARAVEL="$old_laravel" LARAVEL="$laravel" python3 - <<'PY'
import os
import re

lines = []
for name, old, new, package in (
    ("WordPress", "OLD_WORDPRESS", "WORDPRESS", "johnpbloch/wordpress"),
    ("Laravel", "OLD_LARAVEL", "LARAVEL", "laravel/framework"),
):
    before, after = os.environ[old], os.environ[new]
    if before != after:
        lines.append(
            f"- The lock pins **{name} {after}** (it pinned {before}). An existing project gets it with "
            f"`composer update {package} --with-dependencies`."
        )

with open("CHANGELOG.md") as handle:
    text = handle.read()

unreleased = re.search(r"^## \[Unreleased\]\([^)]*\)\n", text, re.M)
if unreleased is None:
    raise SystemExit("CHANGELOG.md has no [Unreleased] section")

following = re.search(r"^## \[", text[unreleased.end():], re.M)
end = unreleased.end() + following.start() if following else len(text)
pending = text[unreleased.end():end].strip("\n")

if "### Changed" in pending:
    pending = pending.replace("### Changed\n", "### Changed\n\n" + "\n".join(lines) + "\n", 1)
else:
    pending = (pending + "\n\n" if pending else "") + "### Changed\n\n" + "\n".join(lines)

with open("CHANGELOG.md", "w") as handle:
    handle.write(text[: unreleased.end()] + "\n" + pending + "\n\n" + text[end:])
PY

echo "changed=true"
echo "wordpress=$wordpress"
echo "laravel=$laravel"
