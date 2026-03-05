#!/usr/bin/env bash
# pack.sh — Installs production Composer dependencies and packs vendor/ into
# api/vendor.tar.gz for embedding into the Go serverless binary.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
OUTPUT="$PROJECT_ROOT/api/vendor.tar.gz"

echo "==> Installing production Composer dependencies..."
cd "$PROJECT_ROOT"
composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative

echo "==> Packing vendor/ -> api/vendor.tar.gz ..."
# Exclude tests and doc files to keep the archive smaller
tar -czf "$OUTPUT" \
    --exclude='vendor/*/tests' \
    --exclude='vendor/*/Tests' \
    --exclude='vendor/*/Test' \
    --exclude='vendor/*/*/tests' \
    --exclude='vendor/*/doc' \
    --exclude='vendor/*/docs' \
    --exclude='vendor/*/examples' \
    --exclude='vendor/*/.git' \
    vendor/

SIZE=$(du -sh "$OUTPUT" | cut -f1)
echo "==> Done! $OUTPUT ($SIZE)"
