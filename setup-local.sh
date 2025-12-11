#!/bin/bash

echo "🚀 Configurando ION para ejecución local..."

# Copiar .env.example a .env
echo "📝 Creando archivo .env..."
cp .env.example .env

# Configurar para usar SQLite (más fácil para desarrollo local)
echo "⚙️ Configurando base de datos SQLite..."
sed -i '' 's/DB_CONNECTION=sqlite/DB_CONNECTION=sqlite/' .env
sed -i '' 's/# DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/' .env

# Crear base de datos SQLite
echo "🗄️ Creando base de datos..."
touch database/database.sqlite

# Instalar dependencias
echo "📦 Instalando dependencias de Composer..."
composer install

# Generar clave de aplicación
echo "🔑 Generando APP_KEY..."
php artisan key:generate

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate

# Crear enlace de storage
echo "🔗 Creando enlace de storage..."
php artisan storage:link

echo "✅ ¡ION está listo!"
echo ""
echo "Para iniciar el servidor ejecuta:"
echo "php artisan serve"
echo ""
echo "Luego abre: http://localhost:8000"
