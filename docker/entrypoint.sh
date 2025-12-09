#!/bin/bash
set -e

echo "🚀 Starting ION on Apache..."

cd /var/www/html

# Generate app key if not exists
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force || echo "⚠️ Migrations failed or already run"

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force || true

# Set permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Starting Apache..."
exec apache2-foreground
