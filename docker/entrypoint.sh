#!/bin/bash
set -e

echo "🚀 Starting ION on Apache..."

cd /var/www/html

# Generate app key first (if missing)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "🔑 Generating application key..."
    GENERATED_KEY=$(php artisan key:generate --show)
    export APP_KEY="base64:${GENERATED_KEY}"
fi

# Parse DATABASE_URL if it exists (for PostgreSQL on Render)
if [ -n "$DATABASE_URL" ]; then
    echo "🔗 Parsing DATABASE_URL..."
    # Extract components from DATABASE_URL
    # Format: postgresql://user:password@host:port/database
    DB_USER=$(echo $DATABASE_URL | sed -n 's/.*:\/\/\([^:]*\):.*/\1/p')
    DB_PASSWORD=$(echo $DATABASE_URL | sed -n 's/.*:\/\/[^:]*:\([^@]*\)@.*/\1/p')
    DB_HOST=$(echo $DATABASE_URL | sed -n 's/.*@\([^:]*\):.*/\1/p')
    DB_PORT=$(echo $DATABASE_URL | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    DB_DATABASE=$(echo $DATABASE_URL | sed -n 's/.*\/\(.*\)/\1/p')
else
    DB_HOST="${DB_HOST:-127.0.0.1}"
    DB_PORT="${DB_PORT:-5432}"
    DB_DATABASE="${DB_DATABASE:-laravel}"
    DB_USER="${DB_USER:-root}"
    DB_PASSWORD="${DB_PASSWORD:-}"
fi

# Create .env file from environment variables (now with proper APP_KEY)
echo "📝 Creating .env file..."
cat > .env << EOF
APP_NAME="${APP_NAME:-ION}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG=true
APP_URL="${APP_URL:-https://ion-rsed.onrender.com}"

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION="${DB_CONNECTION:-pgsql}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USER}"
DB_PASSWORD="${DB_PASSWORD}"

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"
EOF

# Regenerate composer autoloader
echo "🔄 Regenerating composer autoloader..."
composer dump-autoload --optimize --no-interaction --ignore-platform-reqs

# Clear ALL caches
echo "🧹 Clearing all caches..."
rm -rf bootstrap/cache/*.php
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force || echo "⚠️ Migrations failed or already run"

# Run seeders (only SuperAdmin for production)
echo "👤 Creating SuperAdmin user..."
php artisan db:seed --class=SuperAdminSeeder --force || echo "⚠️ Seeder already run or failed"


# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force || true

# Set permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Starting Apache..."
exec apache2-foreground
