#!/usr/bin/env sh
set -e

chmod -R 775 storage
chmod -R 775 bootstrap/cache

php artisan storage:link || true

php artisan migrate --force

php artisan db:seed --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

apache2-foreground
