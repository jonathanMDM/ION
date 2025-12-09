<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure default company exists
        $company = Company::firstOrCreate(
            ['name' => 'Empresa Principal'],
            [
                'email' => 'admin@paladin.com',
                'status' => 'active'
            ]
        );

        // Create SuperAdmin User
        $user = User::updateOrCreate(
            ['email' => 'superadmin@paladin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'company_id' => $company->id,
                'role' => 'admin',
                'is_superadmin' => true,
                'is_active' => true,
            ]
        );

        $this->command->info('SuperAdmin created successfully.');
        $this->command->info('Email: superadmin@paladin.com');
        $this->command->info('Password: password');
    }
}
