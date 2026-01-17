# Sprint 1 - Control Financiero y Contable

## ✅ Implementado

### 1. Depreciación de Activos

**Campos añadidos a `assets`**:

- `depreciation_method`: Método de depreciación (línea recta, saldo decreciente, unidades de producción, ninguno)
- `useful_life_years`: Vida útil en años
- `salvage_value`: Valor de salvamento/residual
- `depreciation_start_date`: Fecha de inicio de depreciación
- `accumulated_depreciation`: Depreciación acumulada (calculada)
- `cost_center_id`: Relación con centro de costo

**Métodos de cálculo implementados**:

- `getBookValueAttribute()`: Calcula el valor en libros actual
- `calculateAnnualDepreciation()`: Calcula depreciación anual según método
- `calculateDepreciationToDate($date)`: Calcula depreciación hasta una fecha específica
- `updateDepreciation()`: Actualiza la depreciación acumulada
- `getDepreciationPercentageAttribute()`: Obtiene porcentaje de depreciación
- `isFullyDepreciated()`: Verifica si el activo está totalmente depreciado

**Comando Artisan**:

```bash
php artisan assets:calculate-depreciation
php artisan assets:calculate-depreciation --company=1
```

### 2. Centros de Costo

**Nueva tabla**: `cost_centers`

- Código único por centro
- Nombre y descripción
- Presupuesto asignado
- Responsable (manager)
- Estado activo/inactivo

**Modelo `CostCenter`**:

- Relación con Company
- Relación con Manager (User)
- Relación con Assets
- Métodos para calcular valor total de activos
- Método para verificar si se excedió el presupuesto

### 3. Costos Asociados

**Nueva tabla**: `asset_costs`

- Tipos de costo: mantenimiento, reparación, seguro, repuestos, mejora, otro
- Monto, descripción, fecha
- Número de factura y proveedor
- Ruta para documento/comprobante
- Usuario que registró el costo

**Modelo `AssetCost`**:

- Relación con Asset
- Relación con User (creator)
- Método para formatear tipo de costo

**Relación en Asset**:

- `costs()`: Todos los costos del activo
- `getTotalCostsAttribute()`: Total de costos acumulados

## 📊 Estructura de Base de Datos

### Tabla: cost_centers

```sql
id
company_id (FK)
code (unique)
name
description
budget
manager_id (FK users)
is_active
created_at
updated_at
```

### Tabla: asset_costs

```sql
id
asset_id (FK)
cost_type (enum)
amount
description
date
invoice_number
vendor
document_path
created_by (FK users)
created_at
updated_at
```

### Modificaciones a assets

```sql
depreciation_method (enum)
useful_life_years (integer)
salvage_value (decimal)
depreciation_start_date (date)
accumulated_depreciation (decimal)
cost_center_id (FK cost_centers)
```

## 🎯 Próximos Pasos

### Fase 1.1: Interfaces de Usuario

- [ ] CRUD de Centros de Costo
- [ ] Formulario de registro de costos en activos
- [ ] Vista de depreciación en detalle de activo
- [ ] Dashboard financiero

### Fase 1.2: Reportes

- [ ] Reporte de depreciación por activo
- [ ] Reporte de depreciación por centro de costo
- [ ] Reporte de costos por tipo
- [ ] Reporte de valor en libros vs valor de compra

### Fase 1.3: Automatización

- [ ] Programar cálculo automático mensual de depreciación
- [ ] Alertas de costos excesivos
- [ ] Alertas de presupuesto excedido en centros de costo

## 🔧 Uso

### Calcular Depreciación

```php
// Para un activo específico
$asset->updateDepreciation();

// Obtener valor en libros
$bookValue = $asset->book_value;

// Verificar si está totalmente depreciado
if ($asset->isFullyDepreciated()) {
    // ...
}
```

### Registrar Costos

```php
$asset->costs()->create([
    'cost_type' => 'maintenance',
    'amount' => 150000,
    'description' => 'Cambio de aceite',
    'date' => now(),
    'created_by' => auth()->id(),
]);

// Obtener total de costos
$totalCosts = $asset->total_costs;
```

### Centros de Costo

```php
// Crear centro de costo
$costCenter = CostCenter::create([
    'company_id' => 1,
    'code' => 'CC001',
    'name' => 'Administración',
    'budget' => 50000000,
    'manager_id' => 5,
]);

// Asignar activo a centro de costo
$asset->update(['cost_center_id' => $costCenter->id]);

// Verificar presupuesto
if ($costCenter->isBudgetExceeded()) {
    // Alerta de presupuesto excedido
}
```

## 📝 Notas Técnicas

- Todas las tablas incluyen `company_id` para multi-tenancy
- Los cálculos de depreciación se pueden ejecutar manualmente o programar
- Los métodos de depreciación soportados son:
    - **Línea Recta**: Depreciación constante cada año
    - **Saldo Decreciente**: Depreciación mayor al inicio
    - **Unidades de Producción**: Requiere datos adicionales de uso
- Los valores monetarios usan `decimal(15,2)` para precisión
- Se mantiene un registro de auditoría mediante el trait `LogActivity`

## 🚀 Deployment

Para aplicar en producción:

```bash
git add .
git commit -m "Feat: Implementado Sprint 1 - Control Financiero y Contable"
git push origin develop

# En el servidor
cd /var/www/ion-dev
git pull origin develop
php artisan migrate
php artisan assets:calculate-depreciation
```
