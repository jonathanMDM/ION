# 📘 Guía de Módulos Financieros - ION Inventory

## ❓ Preguntas Frecuentes

### 1️⃣ ¿Dónde puedo agregar Centros de Costo?

**Respuesta**: Necesitas añadir un enlace en el menú lateral (sidebar).

**Ubicación del archivo**: `resources/views/layouts/app.blade.php`

**Código a añadir** (después de la línea 234, después de la sección de Proveedores):

```blade
@if(auth()->user()->company->hasModule('financial_control'))
<div class="mt-4">
    <div class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] sidebar-text">Finanzas</div>
    @if(Auth::user()->isAdmin())
    <a href="{{ route('cost-centers.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all hover:bg-white/5 hover:text-white mb-1 {{ request()->routeIs('cost-centers.*') ? 'sidebar-item-active' : '' }}" title="Centros de Costo">
        <i class="fas fa-building w-6"></i>
        <span class="ml-3 sidebar-text font-medium text-sm truncate">Centros de Costo</span>
    </a>
    @endif
</div>
@endif
```

**Ruta para acceder**: Una vez añadido el enlace, podrás ir a:

- **URL**: `/cost-centers`
- **Menú**: Sidebar → Finanzas → Centros de Costo

---

### 2️⃣ ¿Al seleccionar un Método de Depreciación debería aparecer algo?

**Respuesta**: ¡SÍ! Cuando seleccionas un método de depreciación (que no sea "Sin depreciación"), deberían aparecer automáticamente los campos:

- **Vida Útil (años)**
- **Valor de Salvamento**
- **Fecha de Inicio de Depreciación**

**¿Por qué no aparecen?**

El JavaScript que controla esto está en el archivo `resources/views/assets/create.blade.php` al final:

```javascript
function toggleDepreciationFields() {
    const method = document.getElementById("depreciation_method").value;
    const fields = document.getElementById("depreciation_fields");

    if (method === "none") {
        fields.style.display = "none";
    } else {
        fields.style.display = "contents";
    }
}
```

**Posibles problemas**:

1. El JavaScript no se está cargando
2. Los IDs no coinciden
3. Hay un error de JavaScript en la consola del navegador

**Cómo verificar**:

1. Abre el navegador
2. Presiona F12 (Herramientas de Desarrollador)
3. Ve a la pestaña "Console"
4. Busca errores en rojo
5. Intenta seleccionar un método de depreciación y ve si aparece algún error

---

### 3️⃣ ¿Cómo funciona el sistema de módulos?

**Sistema de Habilitación de Módulos**:

1. **Superadmin** habilita/deshabilita módulos por empresa
    - Va a: `Superadmin` → `Empresas` → `Editar`
    - Marca/desmarca los módulos deseados

2. **Verificación en el código**:

```php
@if(auth()->user()->company->hasModule('financial_control'))
    // Mostrar funcionalidad avanzada
@endif
```

3. **Módulos Disponibles**:
    - ✅ `financial_control` - Control Financiero (implementado)
    - ✅ `depreciation` - Depreciación (implementado)
    - ✅ `cost_centers` - Centros de Costo (implementado)
    - ✅ `asset_costs` - Costos Asociados (implementado)
    - ⏳ `transfers` - Transferencias (futuro)
    - ⏳ `loans` - Préstamos (futuro)
    - ⏳ `disposals` - Bajas (futuro)
    - ⏳ `advanced_audit` - Auditoría (futuro)
    - ⏳ `compliance` - Cumplimiento (futuro)

---

## 🔧 Solución Rápida

### Para añadir el enlace de Centros de Costo al menú:

**Opción 1: Manual**

1. Abre `resources/views/layouts/app.blade.php`
2. Busca la línea que dice `@endif` después de "Proveedores" (línea ~234)
3. Añade el código mostrado arriba

**Opción 2: Comando**

```bash
# Desde el directorio del proyecto
git add -A
git commit -m "Añadido enlace de Centros de Costo al sidebar"
git push origin develop
```

---

## 🐛 Debugging del JavaScript

Si los campos de depreciación no aparecen:

1. **Verifica que el módulo esté habilitado**:

```php
// En resources/views/assets/create.blade.php
@if(auth()->user()->company->hasModule('financial_control'))
```

2. **Verifica los IDs en el HTML**:

- `depreciation_method` (select)
- `depreciation_fields` (div contenedor)

3. **Prueba en la consola del navegador**:

```javascript
// Abre F12 → Console
document.getElementById("depreciation_method");
document.getElementById("depreciation_fields");
toggleDepreciationFields();
```

---

## 📞 Próximos Pasos

1. **Añadir enlace al sidebar** (manual o con commit)
2. **Verificar JavaScript** (F12 → Console)
3. **Probar creación de activo** con depreciación
4. **Crear primer Centro de Costo**

¿Necesitas ayuda con alguno de estos pasos?
