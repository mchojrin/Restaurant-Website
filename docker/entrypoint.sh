#!/bin/sh
# Runs as root before Apache starts (the official docker-php-entrypoint then
# execs apache2-foreground, which drops privileges for the workers).
# Make sure the image upload directories exist and are writable before Apache
# starts. On first run the named volume is seeded from the image, so ownership
# is normally correct already; this keeps it correct after restores/rebuilds.
set -e

IMAGES_DIR=/var/www/html/images

mkdir -p "$IMAGES_DIR/food" "$IMAGES_DIR/category"
chown -R www-data:www-data "$IMAGES_DIR"
chmod -R u+rwX,g+rwX "$IMAGES_DIR"

exec "$@"
