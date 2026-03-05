#!/usr/bin/env bash
# setup.sh — Downloads a static PHP-FPM 8.2 binary (Linux x86_64) into api/bin/php-fpm
# Run this once locally or in CI before deploying.
set -euo pipefail

# ---------------------------------------------------------------------------
# Configuration — update PHP_VERSION when upgrading
# Latest builds at: https://dl.static-php.dev/static-php-cli/bulk/
# ---------------------------------------------------------------------------
PHP_VERSION="8.4.18"
BINARY_URL="https://dl.static-php.dev/static-php-cli/bulk/php-${PHP_VERSION}-fpm-linux-x86_64.tar.gz"
DEST="$(dirname "$0")/../api/bin/php-fpm"

echo "==> Downloading static PHP-FPM ${PHP_VERSION} binary..."
mkdir -p "$(dirname "$DEST")"

TMP_DIR=$(mktemp -d)
trap "rm -rf $TMP_DIR" EXIT

curl -fsSL "$BINARY_URL" -o "$TMP_DIR/php.tar.gz"

echo "==> Extracting..."
tar -xzf "$TMP_DIR/php.tar.gz" -C "$TMP_DIR"

# The archive contains a 'php-fpm' binary
if [ -f "$TMP_DIR/php-fpm" ]; then
    cp "$TMP_DIR/php-fpm" "$DEST"
elif [ -f "$TMP_DIR/php" ]; then
    cp "$TMP_DIR/php" "$DEST"
else
    echo "ERROR: Could not find php or php-fpm binary in archive." >&2
    ls "$TMP_DIR"
    exit 1
fi

chmod +x "$DEST"
echo "==> PHP-FPM binary installed to: $DEST"

# Binary is Linux x86_64 — only verify version when running on Linux
if [[ "$(uname -s)" == "Linux" ]]; then
    "$DEST" --version
else
    echo "==> (skipping --version check: binary is Linux x86_64, current OS is $(uname -s))"
fi
echo "==> Done."
