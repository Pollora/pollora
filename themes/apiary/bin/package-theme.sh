#!/bin/bash
set -euo pipefail

# Package the apiary development theme into the theme-apiary template.
#
# Usage: ./package-theme.sh <source-theme-path>
#
# Example:
#   ./package-theme.sh ~/Sites/pollora-test/themes/apiary
#
# This script:
# 1. Copies theme files (excluding node_modules, locks, build artifacts)
# 2. Replaces "apiary" / "Apiary" with placeholders
# 3. Shows a diff for review before committing

SOURCE="${1:?Usage: $0 <source-theme-path>}"
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
TARGET_DIR="$(dirname "$SCRIPT_DIR")"

# The dev theme code name and its StudlyCase variant
CODE_NAME="apiary"
CODE_STUDLY="Apiary"
CODE_CAMEL="apiary"

echo "=== Packaging theme ==="
echo "  Source: $SOURCE"
echo "  Target: $TARGET_DIR"
echo ""

if [ ! -d "$SOURCE" ]; then
    echo "Error: Source directory not found: $SOURCE"
    exit 1
fi

# Sync files
echo "Syncing files..."
rsync -av --delete \
    --exclude='node_modules' \
    --exclude='package-lock.json' \
    --exclude='yarn.lock' \
    --exclude='.git' \
    --exclude='.claude' \
    --exclude='package-theme.sh' \
    --exclude='bin/' \
    --exclude='README.md' \
    --exclude='languages/*.mo' \
    --exclude='screenshot.png' \
    --exclude='tailwind.safelist.txt' \
    "$SOURCE/" "$TARGET_DIR/" \
    --quiet

echo "Replacing code name with placeholders..."

find "$TARGET_DIR" -type f \
    -not -path "*/.git/*" \
    -not -path "*/node_modules/*" \
    -not -name "package-theme.sh" \
    -not -name "README.md" \
    -not -name "requirements.json" \
    -not -name "*.woff2" \
    -not -name "*.png" \
    -not -name "*.jpg" \
    -not -name "*.svg" \
    -not -name "*.mo" \
    | while read -r file; do
        sed -i \
            -e "s|Theme\\\\${CODE_STUDLY}|%theme_namespace%|g" \
            -e "s|Theme Name: ${CODE_STUDLY}|Theme Name: %theme_name%|g" \
            -e "s|Text Domain: ${CODE_NAME}|Text Domain: %theme_name%|g" \
            -e "s|'name' => '${CODE_NAME}'|'name' => '%theme_name%'|g" \
            -e "s|'${CODE_NAME}/|'%theme_name%/|g" \
            -e "s|${CODE_STUDLY} Theme Functions|%theme_name% Theme Functions|g" \
            -e "s|${CODE_NAME} Theme Functions|%theme_name% Theme Functions|g" \
            -e "s|register ${CODE_STUDLY} theme|register %theme_name% theme|g" \
            -e "s|register ${CODE_NAME} theme|register %theme_name% theme|g" \
            -e "s|Theme URI: https://amphibee.fr/|Theme URI: %theme_uri%|g" \
            -e "s|Description: WooCommerce Theme based on Tailwind UI|Description: %theme_description%|g" \
            -e "s|Author: AmphiBee|Author: %theme_author%|g" \
            -e "s|Author URI: https://amphibee.fr/|Author URI: %theme_author_uri%|g" \
            -e "s|Version: [0-9.]*|Version: %theme_version%|g" \
            -e "s|'appName' => '${CODE_STUDLY}'|'appName' => '%theme_name%'|g" \
            -e "s|'appShortName' => '${CODE_STUDLY}'|'appShortName' => '%theme_name%'|g" \
            -e "s|${CODE_CAMEL}Cart|%theme_camel%Cart|g" \
            -e "s|${CODE_CAMEL}Search|%theme_camel%Search|g" \
            -e "s|, '${CODE_NAME}')|, '%theme_name%')|g" \
            -e "s|, '${CODE_NAME}' )|, '%theme_name%' )|g" \
            -e "s|'${CODE_NAME}' )|'%theme_name%' )|g" \
            -e "s|textdomain('${CODE_NAME}'|textdomain('%theme_name%'|g" \
            -e "s|${CODE_NAME}_recently_viewed|%theme_name%_recently_viewed|g" \
            -e "s|${CODE_CAMEL}RecentlyViewed|%theme_camel%RecentlyViewed|g" \
            -e "s|${CODE_NAME}-toast|%theme_name%-toast|g" \
            -e "s|${CODE_NAME}-notice|%theme_name%-notice|g" \
            -e "s|\.${CODE_NAME}-toast|.%theme_name%-toast|g" \
            -e "s|\.${CODE_NAME}-notice|.%theme_name%-notice|g" \
            -e "s|'${CODE_NAME}/search/products'|'%theme_name%/search/products'|g" \
            -e "s|'${CODE_NAME}_hide_page_title'|'%theme_name%_hide_page_title'|g" \
            -e "s|'${CODE_NAME}_hide_page_title_nonce'|'%theme_name%_hide_page_title_nonce'|g" \
            -e "s|do_action('${CODE_NAME}_|do_action('%theme_name%_|g" \
            -e "s|${CODE_STUDLY} theme|%theme_name% theme|g" \
            -e "s|\`${CODE_NAME}/search/products\`|\`%theme_name%/search/products\`|g" \
            "$file"
    done

# Rename language files
if [ -f "$TARGET_DIR/languages/${CODE_NAME}.pot" ]; then
    mv "$TARGET_DIR/languages/${CODE_NAME}.pot" "$TARGET_DIR/languages/%theme_name%.pot"
    echo "Renamed language file: ${CODE_NAME}.pot → %theme_name%.pot"
fi

echo ""
echo "=== Done ==="
echo ""
echo "Review changes:"
echo "  cd $TARGET_DIR && git diff"
echo ""
echo "Then commit, tag and push:"
echo "  git add -A && git commit -m 'feat: update theme template'"
echo "  git tag x.y.z && git push origin main --tags"
