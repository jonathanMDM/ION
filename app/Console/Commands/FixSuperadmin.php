<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FixSuperadmin extends Command
{
    protected $signature = 'superadmin:fix';
    protected $description = 'Fix superadmin user to have no company_id';

    public function handle()
    {
        $user = User::where('email', 'superadmin@paladin.com')->first();
        
        if (!$user) {
            $this->error('Superadmin not found!');
            return 1;
        }
        
        $this->info('Found user: ' . $user->name . ' (ID: ' . $user->id . ')');
        $this->info('Current role: ' . $user->role);
        $this->info('Current company_id: ' . ($user->company_id ?? 'null'));
        
        $user->role = 'superadmin';
        $user->company_id = null;
        $user->save();
        
        $this->info('✓ User updated successfully!');
        $this->info('New role: ' . $user->role);
        $this->info('New company_id: null');
        
        return 0;
    }
}
