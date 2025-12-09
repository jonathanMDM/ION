<?php

namespace App\Config;

class PermissionConfig
{
    /**
     * All available permissions organized by category
     */
    public static function getAllPermissions(): array
    {
        return [
            // Activos
            'view_assets' => 'Ver listado de activos',
            'create_assets' => 'Crear nuevos activos',
            'edit_assets' => 'Editar activos existentes',
            'delete_assets' => 'Eliminar activos',
            'import_assets' => 'Importar activos desde Excel',
            'export_assets' => 'Exportar activos a Excel',

            // Reportes
            'view_reports' => 'Ver reportes',
            'create_reports' => 'Generar reportes personalizados',
            'export_reports_pdf' => 'Exportar reportes a PDF',
            'export_reports_excel' => 'Exportar reportes a Excel',

            // Organización
            'manage_locations' => 'Gestionar ubicaciones',
            'manage_categories' => 'Gestionar categorías',
            'manage_subcategories' => 'Gestionar subcategorías',
            'manage_suppliers' => 'Gestionar proveedores',

            // Mantenimiento
            'view_maintenance' => 'Ver historial de mantenimiento',
            'create_maintenance' => 'Registrar mantenimiento',
            'edit_maintenance' => 'Editar registros de mantenimiento',
            'delete_maintenance' => 'Eliminar registros de mantenimiento',

            // Empleados
            'view_employees' => 'Ver empleados',
            'create_employees' => 'Crear empleados',
            'edit_employees' => 'Editar empleados',
            'delete_employees' => 'Eliminar empleados',

            // Movimientos
            'view_movements' => 'Ver movimientos de activos',
            'create_movements' => 'Registrar movimientos',

            // Usuarios
            'view_users' => 'Ver usuarios',
            'create_users' => 'Crear usuarios',
            'edit_users' => 'Editar usuarios',
            'delete_users' => 'Eliminar usuarios',
            'impersonate_users' => 'Suplantar usuarios',

            // Sistema
            'manage_backups' => 'Gestionar respaldos',
            'view_activity_logs' => 'Ver logs de actividad',
            'manage_webhooks' => 'Gestionar webhooks',
            'manage_settings' => 'Gestionar configuración',
        ];
    }

    /**
     * Get permissions organized by category
     */
    public static function getPermissionsByCategory(): array
    {
        return [
            'Activos' => [
                'view_assets' => 'Ver listado de activos',
                'create_assets' => 'Crear nuevos activos',
                'edit_assets' => 'Editar activos existentes',
                'delete_assets' => 'Eliminar activos',
                'import_assets' => 'Importar activos desde Excel',
                'export_assets' => 'Exportar activos a Excel',
            ],
            'Reportes' => [
                'view_reports' => 'Ver reportes',
                'create_reports' => 'Generar reportes personalizados',
                'export_reports_pdf' => 'Exportar reportes a PDF',
                'export_reports_excel' => 'Exportar reportes a Excel',
            ],
            'Organización' => [
                'manage_locations' => 'Gestionar ubicaciones',
                'manage_categories' => 'Gestionar categorías',
                'manage_subcategories' => 'Gestionar subcategorías',
                'manage_suppliers' => 'Gestionar proveedores',
            ],
            'Mantenimiento' => [
                'view_maintenance' => 'Ver historial de mantenimiento',
                'create_maintenance' => 'Registrar mantenimiento',
                'edit_maintenance' => 'Editar registros de mantenimiento',
                'delete_maintenance' => 'Eliminar registros de mantenimiento',
            ],
            'Empleados' => [
                'view_employees' => 'Ver empleados',
                'create_employees' => 'Crear empleados',
                'edit_employees' => 'Editar empleados',
                'delete_employees' => 'Eliminar empleados',
            ],
            'Movimientos' => [
                'view_movements' => 'Ver movimientos de activos',
                'create_movements' => 'Registrar movimientos',
            ],
            'Usuarios' => [
                'view_users' => 'Ver usuarios',
                'create_users' => 'Crear usuarios',
                'edit_users' => 'Editar usuarios',
                'delete_users' => 'Eliminar usuarios',
                'impersonate_users' => 'Suplantar usuarios',
            ],
            'Sistema' => [
                'manage_backups' => 'Gestionar respaldos',
                'view_activity_logs' => 'Ver logs de actividad',
                'manage_webhooks' => 'Gestionar webhooks',
                'manage_settings' => 'Gestionar configuración',
            ],
        ];
    }

    /**
     * Get default permissions for each role
     */
    public static function getRolePermissions(string $role): array
    {
        $permissions = [
            'viewer' => [
                // Solo lectura
                'view_assets',
                'view_reports',
                'view_maintenance',
                'view_employees',
                'view_movements',
            ],
            'editor' => [
                // Todos los de viewer
                'view_assets',
                'view_reports',
                'view_maintenance',
                'view_employees',
                'view_movements',
                // Más permisos de creación/edición
                'create_assets',
                'edit_assets',
                'delete_assets',
                'import_assets',
                'export_assets',
                'create_reports',
                'export_reports_pdf',
                'export_reports_excel',
                'manage_locations',
                'manage_categories',
                'manage_subcategories',
                'manage_suppliers',
                'create_maintenance',
                'edit_maintenance',
                'delete_maintenance',
                'create_employees',
                'edit_employees',
                'delete_employees',
                'create_movements',
            ],
            'admin' => array_keys(self::getAllPermissions()), // Todos los permisos
        ];

        return $permissions[$role] ?? [];
    }

    /**
     * Get role description
     */
    public static function getRoleDescription(string $role): string
    {
        $descriptions = [
            'viewer' => 'Solo lectura - Puede ver información pero no modificarla',
            'editor' => 'Crear y editar - Puede gestionar activos y datos, pero no usuarios',
            'admin' => 'Acceso completo - Puede gestionar todo el sistema incluyendo usuarios',
            'custom' => 'Permisos personalizados - Selecciona permisos específicos',
        ];

        return $descriptions[$role] ?? '';
    }
}
