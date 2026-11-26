#!/usr/bin/env bash
#
# Fails when composer.lock is behind the latest stable WordPress, Laravel or
# Pollora framework: `composer create-project` installs what the tag's lock
# pins, so a release carries exactly these versions.
#
# WordPress is read from wordpress.org, not from Packagist: the Composer package
# is a third-party mirror and can lag. A source that cannot be read fails the
# check, it never passes it.
#
# .github/upstream-exceptions.json (optional) can hold back on a branch:
#   [{"package": "wordpress", "branch": "7.1", "until": "2027-01-15", "reason": "…"}]
# An exception expires after `until`, and only accepts the latest release of the
# branch it names, so it can never hide a missing patch or security release.
# `package` is wordpress, laravel/framework or pollora/framework; `branch` is
# MAJOR.MINOR.

set -euo pipefail

lock=${LOCK:-composer.lock}
exceptions=${EXCEPTIONS:-.github/upstream-exceptions.json}
wordpress_url=${WORDPRESS_URL:-https://api.wordpress.org/core/version-check/1.7/}
packagist_url=${PACKAGIST_URL:-https://repo.packagist.org/p2}
today=${TODAY:-$(date -u +%F)}
failed=0

fatal() {
    echo "::error::$*" >&2
    exit 2
}

fetch() {
    curl -fsSL --retry 3 --max-time 30 "$1" || fatal "cannot read $1"
}

locked() {
    local version
    version=$(jq -r --arg p "$1" '.packages[] | select(.name == $p) | .version' "$lock")
    [ -n "$version" ] || fatal "$1 is not in $lock"
    echo "${version#v}"
}

# Reads versions on stdin, prints the highest one starting with the prefix ($1).
highest() {
    awk -v prefix="$1" 'index($0 ".", prefix) == 1' | sort -V | tail -n 1
}

allowed_by_exception() { # package locked versions
    local package=$1 current=$2 versions=$3 branch latest
    [ -f "$exceptions" ] || return 1
    branch=$(echo "$current" | cut -d. -f1,2)
    jq -e --arg p "$package" --arg b "$branch" --arg today "$today" \
        'any(.[]; .package == $p and .branch == $b and (.until // "") >= $today and ((.reason // "") | length) > 0)' \
        "$exceptions" >/dev/null || return 1
    latest=$(echo "$versions" | highest "$branch.")
    [ "$current" = "$latest" ]
}

compare() { # label package locked latest versions
    local label=$1 package=$2 current=$3 latest=$4 versions=$5
    if [ "$current" = "$latest" ]; then
        echo "ok    $label $current"
    elif allowed_by_exception "$package" "$current" "$versions"; then
        echo "::warning::$label $current is held back by an exception, $latest is out"
    else
        echo "::error::$label is locked at $current, the latest stable release is $latest"
        failed=1
    fi
}

[ -f "$lock" ] || fatal "$lock not found"
if [ -f "$exceptions" ]; then
    jq -e 'type == "array"' "$exceptions" >/dev/null || fatal "$exceptions is not a JSON list"
fi

# WordPress
body=$(fetch "$wordpress_url")
latest=$(echo "$body" | jq -r '[.offers[] | select(.response == "upgrade") | .version][0] // empty') || fatal "unreadable answer from $wordpress_url"
[ -n "$latest" ] || fatal "no latest version in the answer from $wordpress_url"
versions=$(echo "$body" | jq -r '.offers[].version')
compare WordPress wordpress "$(locked johnpbloch/wordpress-core)" "$latest" "$versions"

# Laravel and the framework: latest stable release of the locked major, and a
# warning when a newer major exists (moving to one is a decision, not a bump).
for package in laravel/framework pollora/framework; do
    body=$(fetch "$packagist_url/$package.json")
    versions=$(echo "$body" | jq -r --arg p "$package" '.packages[$p][].version' \
        | sed 's/^v//' | grep -E '^[0-9]+\.[0-9]+\.[0-9]+$') || fatal "no stable release of $package in the answer"
    current=$(locked "$package")
    major=${current%%.*}
    latest=$(echo "$versions" | highest "$major.")
    [ -n "$latest" ] || fatal "no stable release of $package $major.x in the answer"
    compare "$package" "$package" "$current" "$latest" "$versions"
    newest=$(echo "$versions" | sort -V | tail -n 1)
    if [ "${newest%%.*}" -gt "$major" ]; then
        echo "::warning::$package $newest is out, a new major version"
    fi
done

exit "$failed"
