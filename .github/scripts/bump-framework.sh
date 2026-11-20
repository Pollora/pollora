#!/usr/bin/env bash
#
# Moves pollora/framework in composer.lock to the newest version composer.json
# allows, and records it in CHANGELOG.md. Changes the working tree only; the
# workflow commits, pushes and opens the pull request.
#
# Prints, one per line, for $GITHUB_OUTPUT:
#   changed=false                                    the lock is up to date
#   changed=true, old=…, new=…, patches_changed=…    the lock moved
#
# Why the lock: `composer create-project` installs what the tag's lock pins,
# whatever composer.json allows, so a framework tag reaches new projects only
# once a skeleton tag carries it in its lock.

set -euo pipefail

package=pollora/framework

locked_version() {
    jq -r --arg p "$package" '.packages[] | select(.name == $p) | .version' composer.lock
}

locked_patches() {
    jq -cS --arg p "$package" '[.packages[] | select(.name == $p) | .extra.patches // {}]' composer.lock
}

old=$(locked_version)
old_patches=$(locked_patches)

# --no-install: the lock alone changes. --ignore-platform-reqs: the runner
# need not carry the extensions the project asks for to resolve one package.
composer update "$package" --no-install --no-scripts --no-interaction --no-progress \
    --ignore-platform-reqs >&2

new=$(locked_version)

if [ "$old" = "$new" ]; then
    echo "changed=false"
    exit 0
fi

if [ "$old_patches" = "$(locked_patches)" ]; then
    patches_changed=false
else
    patches_changed=true
fi

OLD="$old" NEW="$new" DATE="${BUMP_DATE:-$(date -u +%F)}" python3 - <<'PY'
import os
import re

old, new, date = os.environ["OLD"], os.environ["NEW"], os.environ["DATE"]
repo = "https://github.com/Pollora/pollora"

with open("CHANGELOG.md") as handle:
    text = handle.read()

unreleased = re.search(r"^## \[Unreleased\]\([^)]*\)\n", text, re.M)
if unreleased is None:
    raise SystemExit("CHANGELOG.md has no [Unreleased] section")

next_section = re.search(r"^## \[", text[unreleased.end():], re.M)
body_end = unreleased.end() + (next_section.start() if next_section else len(text) - unreleased.end())
pending = text[unreleased.end():body_end].strip("\n")

bullet = (
    f"- A new project installs `pollora/framework` **{new}**, which the lock now pins "
    f"(it pinned {old}). What changed is in the "
    f"[framework's release notes](https://github.com/Pollora/framework/releases/tag/{new})"
)

if "### Changed" in pending:
    head, _, tail = pending.partition("### Changed")
    following = re.search(r"^### ", tail, re.M)
    if following:
        cut = following.start()
        section = tail[:cut].rstrip("\n") + "\n" + bullet + "\n\n" + tail[cut:]
    else:
        section = tail.rstrip("\n") + "\n" + bullet
    pending = head + "### Changed" + section
else:
    pending = (pending + "\n\n" if pending else "") + "### Changed\n" + bullet

heading = f"## [Unreleased]({repo}/compare/{new}...main)\n\n"
release = f"## [{new}]({repo}/compare/{old}...{new}) - {date}\n\n{pending.rstrip(chr(10))}\n\n"

with open("CHANGELOG.md", "w") as handle:
    handle.write(text[:unreleased.start()] + heading + release + text[body_end:].lstrip("\n"))
PY

echo "changed=true"
echo "old=$old"
echo "new=$new"
echo "patches_changed=$patches_changed"
