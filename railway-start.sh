#!/bin/bash

echo "🚀 Starting ION deployment on Railway..."

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Install dependencies with platform requirements ignored
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Generate app key if not exists
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force --no-interaction
fi

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force --no-interaction

# Clear and cache config
echo "⚙️ Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Seed database (optional - comment out if not needed)
# echo "🌱 Seeding database..."
# php artisan db:seed --force --no-interaction

# Start the server
echo "✅ Starting web server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
