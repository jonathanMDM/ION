#!/bin/bash

echo "🚀 Starting ION deployment on Railway..."

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Run composer scripts that were skipped during build
echo "📦 Running composer post-install scripts..."
composer run-script post-autoload-dump --no-interaction 2>/dev/null || true

# Generate app key if not exists
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force --no-interaction
fi

# Clear all caches first
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force --no-interaction || echo "⚠️ Migrations failed or already run"

# Cache config and routes for production
echo "⚙️ Optimizing application..."
php artisan config:cache
php artisan route:cache  
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Start PHP built-in server with proper binding
echo "✅ Starting web server on 0.0.0.0:$PORT..."
php -S 0.0.0.0:$PORT -t public public/index.php
