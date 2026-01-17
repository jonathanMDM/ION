# Plan de Implementación - Sistema Avanzado de Gestión de Activos ION

## Resumen Ejecutivo

Implementación de módulos avanzados de control financiero, trazabilidad, seguridad y cumplimiento normativo para ION Inventory, con habilitación controlada por el superadministrador.

---

## Fase 1: Control Financiero y Contable

### 1.1 Depreciación de Activos

**Objetivo**: Calcular automáticamente la depreciación de activos según diferentes métodos contables.

**Campos a añadir en `assets`**:

- `depreciation_method` (enum: 'straight_line', 'declining_balance', 'units_of_production', 'none')
- `useful_life_years` (integer)
- `salvage_value` (decimal)
- `depreciation_start_date` (date)
- `accumulated_depreciation` (decimal, calculado)
- `book_value` (decimal, calculado)

**Métodos de depreciación**:

- Línea recta (Straight Line)
- Saldo decreciente (Declining Balance)
- Unidades de producción (Units of Production)

**Funcionalidades**:

- Cálculo automático mensual/anual
- Reporte de depreciación
- Gráficos de valor en libros vs tiempo

---

### 1.2 Costos Asociados

**Objetivo**: Registrar y controlar todos los costos relacionados con cada activo.

**Nueva tabla**: `asset_costs`

```sql
- id
- asset_id (FK)
- cost_type (enum: 'maintenance', 'repair', 'insurance', 'spare_parts', 'other')
- amount (decimal)
- description (text)
- date (date)
- invoice_number (string, nullable)
- vendor (string, nullable)
- created_by (FK users)
- created_at, updated_at
```

**Funcionalidades**:

- Registro de costos por activo
- Total de costos acumulados
- Reportes de costos por tipo
- Alertas de costos excesivos

---

### 1.3 Centros de Costo

**Objetivo**: Asignar activos a centros de costo para control presupuestario.

**Nueva tabla**: `cost_centers`

```sql
- id
- company_id (FK)
- code (string, unique)
- name (string)
- description (text, nullable)
- budget (decimal, nullable)
- manager_id (FK users, nullable)
- is_active (boolean)
- created_at, updated_at
```

**Modificar tabla `assets`**:

- Añadir `cost_center_id` (FK, nullable)

**Funcionalidades**:

- CRUD de centros de costo
- Asignación de activos a centros
- Reportes por centro de costo
- Control presupuestario

---

## Fase 2: Movimientos y Trazabilidad

### 2.1 Transferencias entre Ubicaciones

**Objetivo**: Controlar el movimiento físico de activos entre ubicaciones.

**Nueva tabla**: `asset_transfers`

```sql
- id
- asset_id (FK)
- from_location_id (FK locations)
- to_location_id (FK locations)
- from_responsible_id (FK users, nullable)
- to_responsible_id (FK users, nullable)
- transfer_date (datetime)
- reason (text)
- status (enum: 'pending', 'in_transit', 'completed', 'cancelled')
- requested_by (FK users)
- approved_by (FK users, nullable)
- approved_at (datetime, nullable)
- notes (text, nullable)
- created_at, updated_at
```

**Funcionalidades**:

- Solicitud de transferencia
- Aprobación de transferencias
- Seguimiento en tránsito
- Historial completo de movimientos

---

### 2.2 Préstamos Temporales

**Objetivo**: Controlar asignaciones temporales de activos.

**Nueva tabla**: `asset_loans`

```sql
- id
- asset_id (FK)
- borrower_id (FK users)
- lender_id (FK users)
- loan_date (datetime)
- expected_return_date (date)
- actual_return_date (datetime, nullable)
- status (enum: 'active', 'returned', 'overdue', 'cancelled')
- purpose (text)
- condition_on_loan (text, nullable)
- condition_on_return (text, nullable)
- approved_by (FK users, nullable)
- notes (text, nullable)
- created_at, updated_at
```

**Funcionalidades**:

- Registro de préstamos
- Alertas de vencimiento
- Control de devoluciones
- Historial de préstamos por activo/usuario

---

## Fase 3: Seguridad y Control

### 3.1 Sistema de Permisos Granular

**Objetivo**: Control fino de accesos por módulo y acción.

**Nueva tabla**: `permissions`

```sql
- id
- name (string, unique)
- display_name (string)
- module (string)
- description (text, nullable)
- created_at, updated_at
```

**Nueva tabla**: `role_permissions`

```sql
- id
- role (enum: 'superadmin', 'admin', 'manager', 'user', 'viewer')
- permission_id (FK)
- company_id (FK, nullable)
- created_at, updated_at
```

**Permisos por módulo**:

- `assets.view`, `assets.create`, `assets.edit`, `assets.delete`
- `financial.view`, `financial.edit`
- `transfers.request`, `transfers.approve`
- `loans.create`, `loans.approve`
- `costs.view`, `costs.create`
- `reports.view`, `reports.export`

---

### 3.2 Auditoría Completa

**Objetivo**: Registro detallado de todas las acciones en el sistema.

**Tabla existente mejorada**: `activity_logs`

```sql
Añadir campos:
- ip_address (string)
- user_agent (text)
- changes (json) // antes y después
- risk_level (enum: 'low', 'medium', 'high', 'critical')
- module (string)
- action_type (enum: 'create', 'read', 'update', 'delete', 'export', 'login', 'logout')
```

**Funcionalidades**:

- Log automático de todas las acciones
- Búsqueda avanzada de auditoría
- Reportes de actividad por usuario
- Alertas de acciones sospechosas
- Exportación de logs para cumplimiento

---

## Fase 4: Gestión de Bajas

### 4.1 Proceso de Baja de Activos

**Objetivo**: Controlar el proceso formal de dar de baja activos.

**Nueva tabla**: `asset_disposals`

```sql
- id
- asset_id (FK)
- disposal_type (enum: 'sale', 'donation', 'scrap', 'theft', 'loss', 'obsolete')
- disposal_date (date)
- reason (text)
- authorization_level (enum: 'manager', 'director', 'board')
- requested_by (FK users)
- approved_by (FK users, nullable)
- approved_at (datetime, nullable)
- status (enum: 'pending', 'approved', 'rejected', 'completed')
- sale_value (decimal, nullable)
- buyer_info (text, nullable)
- disposal_certificate (string, nullable) // PDF path
- notes (text, nullable)
- created_at, updated_at
```

**Modificar tabla `assets`**:

- Añadir `disposal_id` (FK, nullable)
- Añadir `disposal_status` (enum: 'active', 'pending_disposal', 'disposed')

**Funcionalidades**:

- Solicitud de baja
- Flujo de aprobaciones
- Generación de actas de baja
- Registro contable de la baja

---

## Fase 5: Cumplimiento Normativo

### 5.1 Configuración de Normas Contables

**Objetivo**: Permitir configurar el sistema según NIIF/IFRS u otras normas.

**Nueva tabla**: `company_settings`

```sql
- id
- company_id (FK)
- setting_key (string)
- setting_value (json)
- created_at, updated_at
```

**Settings clave**:

- `accounting_standard` (NIIF, IFRS, US GAAP, Local)
- `depreciation_default_method`
- `minimum_capitalization_value`
- `fiscal_year_start`
- `currency_settings`

---

### 5.2 Políticas de Control

**Nueva tabla**: `control_policies`

```sql
- id
- company_id (FK)
- policy_type (string)
- title (string)
- description (text)
- rules (json)
- is_active (boolean)
- effective_date (date)
- created_by (FK users)
- created_at, updated_at
```

**Tipos de políticas**:

- Aprobación de compras
- Límites de transferencias
- Requisitos de documentación
- Frecuencia de inventarios físicos

---

## Fase 6: Habilitación por Superadministrador

### 6.1 Módulos Habilitables

**Modificar tabla `companies`**:

```sql
Añadir campos JSON:
- enabled_modules (json)
  {
    "financial_control": true/false,
    "depreciation": true/false,
    "cost_centers": true/false,
    "transfers": true/false,
    "loans": true/false,
    "disposals": true/false,
    "advanced_audit": true/false,
    "compliance": true/false
  }
```

**Panel de Superadmin**:

- Vista de configuración por empresa
- Activar/desactivar módulos
- Configurar límites y restricciones

---

## Cronograma de Implementación

### Sprint 1 (Semana 1-2): Fundamentos

- [ ] Migración de campos financieros en assets
- [ ] Tabla de costos asociados
- [ ] Tabla de centros de costo
- [ ] CRUD básico de centros de costo

### Sprint 2 (Semana 3-4): Depreciación

- [ ] Implementar cálculos de depreciación
- [ ] Comando artisan para cálculo automático
- [ ] Vista de depreciación por activo
- [ ] Reporte de depreciación

### Sprint 3 (Semana 5-6): Transferencias y Préstamos

- [ ] Sistema de transferencias
- [ ] Sistema de préstamos
- [ ] Notificaciones automáticas
- [ ] Reportes de movimientos

### Sprint 4 (Semana 7-8): Seguridad y Auditoría

- [ ] Sistema de permisos granular
- [ ] Mejora de activity_logs
- [ ] Panel de auditoría
- [ ] Alertas de seguridad

### Sprint 5 (Semana 9-10): Bajas y Cumplimiento

- [ ] Sistema de bajas
- [ ] Políticas de control
- [ ] Configuración de normas contables
- [ ] Reportes de cumplimiento

### Sprint 6 (Semana 11-12): Integración y Panel Superadmin

- [ ] Panel de habilitación de módulos
- [ ] Configuración por empresa
- [ ] Testing integral
- [ ] Documentación

---

## Consideraciones Técnicas

### Base de Datos

- Todas las nuevas tablas incluirán `company_id` para multi-tenancy
- Índices en campos de búsqueda frecuente
- Soft deletes donde sea apropiado

### Seguridad

- Middleware para verificar módulos habilitados
- Validación de permisos en cada acción
- Encriptación de datos sensibles

### Performance

- Eager loading para relaciones
- Cache de cálculos pesados (depreciación)
- Jobs en cola para procesos largos

### UI/UX

- Diseño consistente con el sistema actual
- Dashboards específicos por módulo
- Exportación a Excel/PDF de todos los reportes

---

## Próximos Pasos Inmediatos

1. **Validar el plan** con el usuario
2. **Priorizar módulos** según necesidad
3. **Comenzar con Sprint 1**: Fundamentos financieros
4. **Crear migraciones** para nuevas tablas
5. **Implementar modelos** y relaciones

---

**¿Por dónde quieres que comencemos?**
