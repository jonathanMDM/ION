#!/bin/bash
set -e # Detener el script si ocurre algún error

echo "🚀 Iniciando despliegue en PRODUCCIÓN..."

# Asegurarse de estar en la rama correcta
echo "🌿 Sincronizando con GitHub (Rama main)..."
git checkout main
git pull origin main

# Instalar dependencias de PHP
echo "📦 Instalando dependencias de PHP (Producción)..."
composer install --no-dev --optimize-autoloader --no-interaction

# Instalar dependencias de JS y compilar
echo "🎨 Compilando Assets..."
npm install --no-interaction
npm run build

# Limpiar y optimizar configuración
echo "🧹 Optimizando cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones si es necesario
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Recargar PHP-FPM si es necesario (opcional, depende de tu VPS)
# sudo service php8.2-fpm reload

echo "✅ ¡Despliegue en PRODUCCIÓN completado con éxito!"
