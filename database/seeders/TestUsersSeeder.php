<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Config\PermissionConfig;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first company
        $company = Company::first();
        
        if (!$company) {
            $this->command->error('No hay empresas en el sistema. Crea una empresa primero.');
            return;
        }

        $this->command->info('Creando usuarios de prueba para empresa: ' . $company->name);

        // Delete existing test users if they exist
        User::whereIn('email', ['viewer@test.com', 'editor@test.com', 'custom@test.com'])->delete();

        // 1. Usuario Viewer (solo lectura)
        $viewer = User::create([
            'name' => 'Test Viewer',
            'email' => 'viewer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'viewer',
            'company_id' => $company->id,
            'permissions' => json_encode(PermissionConfig::getRolePermissions('viewer')),
        ]);
        $this->command->info('✓ Usuario Viewer creado: viewer@test.com / password123');
        $this->command->info('  Permisos: ' . count(PermissionConfig::getRolePermissions('viewer')) . ' permisos de solo lectura');

        // 2. Usuario Editor
        $editor = User::create([
            'name' => 'Test Editor',
            'email' => 'editor@test.com',
            'password' => Hash::make('password123'),
            'role' => 'editor',
            'company_id' => $company->id,
            'permissions' => json_encode(PermissionConfig::getRolePermissions('editor')),
        ]);
        $this->command->info('✓ Usuario Editor creado: editor@test.com / password123');
        $this->command->info('  Permisos: ' . count(PermissionConfig::getRolePermissions('editor')) . ' permisos de gestión');

        // 3. Usuario con permisos personalizados (solo puede ver y crear activos + ver reportes)
        $customPermissions = ['view_assets', 'create_assets', 'view_reports'];
        $custom = User::create([
            'name' => 'Test Custom',
            'email' => 'custom@test.com',
            'password' => Hash::make('password123'),
            'role' => 'custom',
            'company_id' => $company->id,
            'permissions' => json_encode($customPermissions),
        ]);
        $this->command->info('✓ Usuario Custom creado: custom@test.com / password123');
        $this->command->info('  Permisos: ' . count($customPermissions) . ' permisos personalizados (view_assets, create_assets, view_reports)');

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('Usuarios de prueba creados exitosamente!');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('Credenciales para pruebas:');
        $this->command->info('  Viewer:  viewer@test.com  / password123');
        $this->command->info('  Editor:  editor@test.com  / password123');
        $this->command->info('  Custom:  custom@test.com  / password123');
        $this->command->info('');
    }
}
