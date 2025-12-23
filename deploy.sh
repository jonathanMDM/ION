#!/bin/bash

echo "🚀 Iniciando despliegue..."

# Instalar dependencias de PHP
composer install --no-dev --optimize-autoloader

# Instalar dependencias de JS y compilar
npm install
npm run build

# Limpiar y optimizar configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones si es necesario
php artisan migrate --force

echo "✅ Despliegue completado con éxito."
