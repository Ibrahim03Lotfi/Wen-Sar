#!/usr/bin/env sh
set -e

# Set PHP upload limits for handling multiple high-res camera photos
export PHP_UPLOAD_MAX_FILESIZE=64M
export PHP_POST_MAX_SIZE=128M
export PHP_MAX_FILE_UPLOADS=20
export PHP_MEMORY_LIMIT=1024M
export PHP_MAX_EXECUTION_TIME=600
export PHP_MAX_INPUT_TIME=600

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
