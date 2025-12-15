<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateSuperadminCredentials extends Command
{
    protected $signature = 'superadmin:update-credentials {email} {password}';
    protected $description = 'Update superadmin email and password';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $superadmin = User::where('role', 'superadmin')->first();

        if (!$superadmin) {
            $this->error('Superadmin not found!');
            return 1;
        }

        $oldEmail = $superadmin->email;
        
        $superadmin->email = $email;
        $superadmin->password = Hash::make($password);
        $superadmin->save();

        $this->info("✅ Superadmin credentials updated successfully!");
        $this->info("Old email: {$oldEmail}");
        $this->info("New email: {$email}");
        $this->info("Password: Updated");

        return 0;
    }
}
