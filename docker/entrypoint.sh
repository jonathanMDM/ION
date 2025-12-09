#!/bin/bash
set -e

echo "🚀 Starting ION on Apache..."

cd /var/www/html

# Run composer post-install scripts
echo "📦 Running composer scripts..."
composer run-script post-autoload-dump --no-interaction || true

# Generate app key if not exists
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear  
php artisan route:clear
php artisan view:clear

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force || echo "⚠️ Migrations failed"

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force || true

# Set permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Starting Apache..."
exec apache2-foreground
