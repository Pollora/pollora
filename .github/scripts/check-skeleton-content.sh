#!/usr/bin/env bash
#
# Fails when files of a demo or development site are tracked. `composer
# create-project` copies the tag into every new project, so whatever is committed
# here ships to all of them.

set -euo pipefail

allowed='^(themes/\.gitkeep|public/content/languages/\.gitkeep|app/Cms/\.keep|app/Cms/PostTypes/Book\.php)$'

found=$(git ls-files -- themes Modules modules_statuses.json .claude public/content/languages app/Cms \
    | grep -vE "$allowed" || true)

if [ -n "$found" ]; then
    echo "::error::the skeleton must not ship these files:"
    echo "$found" | head -n 40
    echo "($(echo "$found" | wc -l | tr -d ' ') files)"
    exit 1
fi

echo "ok    no demo or development content"
