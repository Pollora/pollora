#!/usr/bin/env bash
# Tests of check-upstream.sh against local copies of the two sources.
set -uo pipefail

here=$(cd "$(dirname "$0")" && pwd)
dir=$(mktemp -d)
trap 'rm -rf "$dir"' EXIT
mkdir -p "$dir/p2/laravel" "$dir/p2/pollora"
failures=0

cat > "$dir/wp.json" <<JSON
{"offers":[{"response":"upgrade","version":"7.2"},{"response":"autoupdate","version":"7.2"},{"response":"autoupdate","version":"7.1.5"}]}
JSON
echo '{"packages":{"laravel/framework":[{"version":"v14.0.0"},{"version":"v13.10.0"},{"version":"v13.9.0"},{"version":"v13.11.0-RC1"}]}}' > "$dir/p2/laravel/framework.json"
echo '{"packages":{"pollora/framework":[{"version":"v13.44.2"}]}}' > "$dir/p2/pollora/framework.json"

lock() { # wordpress laravel framework
    jq -n --arg w "$1" --arg l "$2" --arg f "$3" '{packages: [
        {name: "johnpbloch/wordpress-core", version: $w},
        {name: "laravel/framework", version: $l},
        {name: "pollora/framework", version: $f}]}' > "$dir/composer.lock"
}

expect() { # name status pattern
    local name=$1 status=$2 pattern=$3 out rc
    out=$(LOCK="$dir/composer.lock" EXCEPTIONS="$dir/exceptions.json" TODAY=2026-12-01 \
        WORDPRESS_URL="${WP:-file://$dir/wp.json}" PACKAGIST_URL="file://$dir/p2" \
        bash "$here/../check-upstream.sh" 2>&1)
    rc=$?
    if [ "$rc" = "$status" ] && grep -qF -- "$pattern" <<<"$out"; then
        echo "ok   $name"
    else
        echo "FAIL $name (exit $rc, expected $status)"
        echo "$out"
        failures=$((failures + 1))
    fi
}

lock 7.2 v13.10.0 v13.44.2
expect "everything current passes" 0 "ok    WordPress 7.2"
expect "a new major is only a warning" 0 "laravel/framework 14.0.0 is out"

lock 7.1.5 v13.10.0 v13.44.2
expect "an older WordPress fails" 1 "WordPress is locked at 7.1.5"

lock 7.2 v13.9.0 v13.44.2
expect "an older Laravel fails, compared as versions" 1 "latest stable release is 13.10.0"

lock 7.2 v13.10.0 v13.44.1
expect "an older framework fails" 1 "pollora/framework is locked at 13.44.1"

exception() { echo "[{\"package\":\"wordpress\",\"branch\":\"7.1\",\"until\":\"$1\",\"reason\":\"$2\"}]" > "$dir/exceptions.json"; }

lock 7.1.5 v13.10.0 v13.44.2
exception 2026-12-15 "7.2 breaks the editor"
expect "an exception holds a branch back" 0 "held back by an exception"
exception 2026-11-30 "7.2 breaks the editor"
expect "an expired exception does not count" 1 "WordPress is locked at 7.1.5"
exception 2026-12-15 ""
expect "an exception without a reason does not count" 1 "WordPress is locked at 7.1.5"

lock 7.1.4 v13.10.0 v13.44.2
exception 2026-12-15 "7.2 breaks the editor"
expect "an exception never hides a missing patch" 1 "WordPress is locked at 7.1.4"
rm "$dir/exceptions.json"

lock 7.2 v13.10.0 v13.44.2
WP="file://$dir/missing.json" expect "an unreachable source fails" 2 "cannot read"
echo '{}' > "$dir/empty.json"
WP="file://$dir/empty.json" expect "an empty answer fails" 2 "unreadable answer"

[ "$failures" = 0 ] || exit 1
