# 🚀 ION - Sistema de Gestión de Activos e Inventario

Sistema completo de gestión de inventario y activos desarrollado con Laravel, PHP y JavaScript. Diseñado para empresas que necesitan control total sobre sus recursos.

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

## 📋 Características Principales

-   ✅ **Gestión de Activos**: Control completo con códigos QR, categorización y seguimiento
-   🔧 **Mantenimiento**: Sistema de mantenimiento preventivo y correctivo
-   📊 **Reportes**: Generación de reportes en PDF y Excel
-   👥 **Multi-Usuario**: Sistema de roles y permisos
-   📱 **Responsive**: Interfaz adaptable a todos los dispositivos
-   🔐 **Seguridad**: Autenticación de dos factores y respaldos automáticos
-   🌐 **API REST**: Endpoints para integraciones externas
-   📦 **Módulos**: Inventario, Mantenimiento, Movimientos, Asignaciones, Reportes

## 🛠️ Tecnologías Utilizadas

-   **Backend**: Laravel 10.x
-   **Frontend**: Blade Templates, JavaScript, Bootstrap
-   **Base de Datos**: MySQL 8.0
-   **Autenticación**: Laravel Sanctum
-   **PDF**: DomPDF
-   **Excel**: PhpSpreadsheet
-   **QR Codes**: SimpleSoftwareIO/simple-qrcode

## 📦 Requisitos Previos

Antes de instalar, asegúrate de tener:

-   PHP >= 8.1
-   Composer
-   MySQL >= 8.0 o MariaDB >= 10.3
-   Node.js >= 16.x y npm
-   Git

## 🚀 Instalación

### 1. Clonar el Repositorio

```bash
git clone https://github.com/jonathanMDM/ION.git
cd ION
```

### 2. Instalar Dependencias de PHP

```bash
composer install
```

### 3. Instalar Dependencias de Node.js

```bash
npm install
```

### 4. Configurar Variables de Entorno

Copia el archivo de ejemplo y configura tus variables:

```bash
cp .env.example .env
```

Edita el archivo `.env` y configura:

```env
APP_NAME=ION
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ion_database
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 5. Generar Clave de Aplicación

```bash
php artisan key:generate
```

### 6. Crear Base de Datos

Crea una base de datos MySQL:

```sql
CREATE DATABASE ion_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Ejecutar Migraciones

```bash
php artisan migrate
```

### 8. Ejecutar Seeders (Opcional)

Para datos de prueba:

```bash
php artisan db:seed
```

### 9. Crear Enlace Simbólico para Storage

```bash
php artisan storage:link
```

### 10. Compilar Assets

**Para desarrollo:**

```bash
npm run dev
```

**Para producción:**

```bash
npm run build
```

### 11. Iniciar el Servidor

```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

## 👤 Credenciales por Defecto

Después de ejecutar los seeders:

**Administrador:**

-   Email: `admin@ion.com`
-   Password: `password`

**Usuario Regular:**

-   Email: `user@ion.com`
-   Password: `password`

⚠️ **IMPORTANTE**: Cambia estas contraseñas en producción.

## 📁 Estructura del Proyecto

```
ION/
├── app/
│   ├── Http/Controllers/    # Controladores
│   ├── Models/              # Modelos Eloquent
│   └── Services/            # Lógica de negocio
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/            # Datos de prueba
├── public/                  # Archivos públicos
├── resources/
│   ├── views/              # Vistas Blade
│   ├── js/                 # JavaScript
│   └── css/                # Estilos
├── routes/
│   ├── web.php             # Rutas web
│   └── api.php             # Rutas API
└── tests/                  # Tests unitarios
```

## 🔧 Configuración Adicional

### Configurar Cola de Trabajos (Queue)

Para procesar trabajos en segundo plano:

```bash
php artisan queue:work
```

### Configurar Tareas Programadas (Cron)

Agrega a tu crontab:

```bash
* * * * * cd /ruta/a/ION && php artisan schedule:run >> /dev/null 2>&1
```

### Configurar Correo Electrónico

En `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ion.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🧪 Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter AssetTest

# Con cobertura
php artisan test --coverage
```

## 📚 Uso de la API

### Autenticación

```bash
POST /api/login
Content-Type: application/json

{
  "email": "admin@ion.com",
  "password": "password"
}
```

### Obtener Activos

```bash
GET /api/assets
Authorization: Bearer {token}
```

### Crear Activo

```bash
POST /api/assets
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Laptop Dell",
  "category_id": 1,
  "serial_number": "ABC123",
  "status": "available"
}
```

## 🐛 Solución de Problemas

### Error: "Class not found"

```bash
composer dump-autoload
```

### Error de permisos en storage

```bash
chmod -R 775 storage bootstrap/cache
```

### Error de migraciones

```bash
php artisan migrate:fresh --seed
```

### Limpiar caché

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🚀 Despliegue en Producción

### 1. Optimizar Aplicación

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### 2. Configurar .env para Producción

```env
APP_ENV=production
APP_DEBUG=false
```

### 3. Configurar Permisos

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 📖 Documentación Adicional

-   [Laravel Documentation](https://laravel.com/docs)
-   [API Documentation](docs/API.md) _(próximamente)_
-   [User Manual](docs/MANUAL.md) _(próximamente)_

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto es privado y propietario de OurDeveloper.

## 👥 Equipo

-   **Sara Curiel** - CEO & Founder
-   **Jonathan Montes** - Lead Developer

## 📧 Contacto

-   **Email**: info@ourdeveloper.com
-   **WhatsApp**: +1 234 567 890
-   **Website**: [OurDeveloper](https://ourdeveloper.com)

## 🙏 Agradecimientos

-   Laravel Framework
-   Bootstrap
-   Font Awesome
-   Todos los contribuidores de código abierto

---

⭐ Si este proyecto te fue útil, considera darle una estrella en GitHub!
