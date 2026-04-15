#!/usr/bin/env sh
set -e

chmod -R 777 storage
chmod -R 777 bootstrap/cache

mkdir -p storage/app/public/logos
mkdir -p storage/app/public/business_images
chmod -R 777 storage/app/public

php artisan storage:link || true

php artisan migrate --force

php artisan db:seed --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

apache2-foreground
