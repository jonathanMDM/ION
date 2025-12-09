#!/bin/bash

echo "🚀 Starting ION deployment on Railway..."

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Run composer scripts that were skipped during build
echo "📦 Running composer post-install scripts..."
php artisan package:discover --ansi || true

# Generate app key if not exists
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force --no-interaction
fi

# Clear all caches
echo "🧹 Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force --no-interaction || echo "⚠️ Migrations failed or already run"

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# DO NOT cache config/routes/views when using PHP built-in server
# This causes issues with service provider discovery

# Start PHP built-in server with proper binding
echo "✅ Starting web server on 0.0.0.0:$PORT..."
php -S 0.0.0.0:$PORT -t public public/index.php
