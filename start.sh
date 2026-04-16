#!/usr/bin/env sh
set -e

# Set PHP upload limits for handling multiple high-res camera photos
export PHP_UPLOAD_MAX_FILESIZE=64M
export PHP_POST_MAX_SIZE=128M
export PHP_MAX_FILE_UPLOADS=20
export PHP_MEMORY_LIMIT=1024M
export PHP_MAX_EXECUTION_TIME=600
export PHP_MAX_INPUT_TIME=600

# Set proper APP_URL for Render.com
export APP_URL=https://$RENDER_EXTERNAL_HOSTNAME

chmod -R 777 storage
chmod -R 777 bootstrap/cache

# Clear caches to ensure new APP_URL takes effect
php artisan config:clear
php artisan cache:clear
chmod -R 777 bootstrap/cache

mkdir -p storage/app/public/logos
mkdir -p storage/app/public/business_images
chmod -R 777 storage/app/public

# Remove existing storage link/file and recreate symlink
rm -rf public/storage
php artisan storage:link

php artisan migrate --force

php artisan db:seed --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

apache2-foreground
