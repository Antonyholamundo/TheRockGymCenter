#!/bin/bash
set -e

echo "Clearing caches..."
php artisan optimize:clear

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Starting Apache..."
exec apache2-foreground
