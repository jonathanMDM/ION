#!/bin/bash
set -e # Detener el script si ocurre algún error

echo "🚀 Iniciando despliegue..."

# Instalar dependencias de PHP
echo "📦 Instalando dependencias de PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# Instalar dependencias de JS y compilar
echo "🎨 Compilando Assets..."
npm install
npm run build

# Limpiar y optimizar configuración
echo "🧹 Optimizando cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones si es necesario
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

echo "✅ ¡Despliegue completado con éxito!"
