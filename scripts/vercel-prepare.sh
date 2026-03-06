#!/bin/bash
set -e

# Copy Laravel source files into api/laravel/ so @vercel/go bundles them.
# At runtime they will be at /var/task/laravel/.
mkdir -p api/laravel
for d in app bootstrap config database public resources routes storage; do
  [ -d "$d" ] && cp -r "$d" api/laravel/ || true
done
for f in artisan composer.json composer.lock; do
  [ -f "$f" ] && cp "$f" api/laravel/ || true
done
echo "==> Laravel files copied to api/laravel/"

# Download and bundle the PHP-FPM Linux binary at build time so there is no
# network download on cold start.
PHP_FPM_DEST="api/php-fpm-bin"
if [ ! -f "$PHP_FPM_DEST" ]; then
  PHP_FPM_URL="${PHP_FPM_URL:-https://dl.static-php.dev/static-php-cli/bulk/php-8.4.17-fpm-linux-x86_64.tar.gz}"
  echo "==> Downloading PHP-FPM binary for bundling..."
  TMP_TAR=$(mktemp /tmp/php-fpm-XXXXXX.tar.gz)
  curl -fsSL "$PHP_FPM_URL" -o "$TMP_TAR"
  # Verify it's actually a gzip file (CDN sometimes returns HTML error pages)
  if ! file "$TMP_TAR" | grep -q "gzip"; then
    echo "ERROR: Downloaded file is not a gzip archive. CDN may be down."
    echo "  URL: $PHP_FPM_URL"
    echo "  Content: $(head -c 200 "$TMP_TAR")"
    rm -f "$TMP_TAR"
    exit 1
  fi
  tar -xzf "$TMP_TAR" -C /tmp php-fpm
  mv /tmp/php-fpm "$PHP_FPM_DEST"
  chmod +x "$PHP_FPM_DEST"
  rm -f "$TMP_TAR"
  echo "==> PHP-FPM binary bundled at $PHP_FPM_DEST ($(du -sh $PHP_FPM_DEST | cut -f1))"
else
  echo "==> PHP-FPM binary already present at $PHP_FPM_DEST"
fi
