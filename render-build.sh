#!/usr/bin/env bash
# exit on error
set -o errexit

echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🔑 Generating application key..."
php artisan key:generate --force

echo "🗄️ Running migrations..."
php artisan migrate --force

echo "🔗 Creating storage link..."
php artisan storage:link

echo "⚙️ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"
