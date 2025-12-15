# Comando para Reiniciar IDs Auto-Incrementales

## ¿Qué hace este comando?

Este comando ajusta los valores de auto-incremento de las tablas para que el próximo ID sea el siguiente al ID más alto existente.

## ¿Cuándo usarlo?

Úsalo cuando hayas eliminado registros y quieras "limpiar" los IDs para que no haya saltos grandes.

⚠️ **IMPORTANTE:** Solo usa este comando cuando NO haya activos asociados a las ubicaciones, categorías o subcategorías que eliminaste.

## Cómo usar el comando

### Opción 1: Reiniciar TODAS las tablas

```bash
php artisan db:reset-ids
```

Esto reiniciará los IDs de:

-   Ubicaciones (locations)
-   Categorías (categories)
-   Subcategorías (subcategories)

### Opción 2: Reiniciar UNA tabla específica

```bash
# Solo ubicaciones
php artisan db:reset-ids --table=locations

# Solo categorías
php artisan db:reset-ids --table=categories

# Solo subcategorías
php artisan db:reset-ids --table=subcategories
```

## En Heroku

Para ejecutar en Heroku:

```bash
heroku run "php artisan db:reset-ids" --app ion-inventory
```

O para una tabla específica:

```bash
heroku run "php artisan db:reset-ids --table=locations" --app ion-inventory
```

## Ejemplo

Si tienes:

-   Ubicación ID: 1683
-   Ubicación ID: 1684
-   Ubicación ID: 1685
-   Ubicación ID: 1686

Después de ejecutar el comando, el próximo ID será **1687** (no 1700 o algún número más alto).

## Protección Automática

El sistema ahora tiene protección automática que **NO permite eliminar**:

✅ **Ubicaciones** con activos asociados
✅ **Categorías** con subcategorías asociadas
✅ **Subcategorías** con activos asociados
✅ **Proveedores** con activos asociados

Si intentas eliminar algo con dependencias, verás un mensaje de error indicando cuántos elementos están asociados.
