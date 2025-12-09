#!/usr/bin/env bash
# exit on error
set -o errexit

echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🔑 Generating application key..."
php artisan key:generate --force --show

echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "🗄️ Running migrations..."
php artisan migrate --force

echo "🔗 Creating storage link..."
php artisan storage:link || true

echo "✅ Build completed successfully!"
