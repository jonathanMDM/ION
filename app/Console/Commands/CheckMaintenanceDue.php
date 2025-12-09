<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Mail\MaintenanceAlert;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class CheckMaintenanceDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:check-maintenance {days=7 : Days lookahead}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for assets due for maintenance and send alerts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->argument('days');
        $this->info("Checking for maintenance due within {$days} days...");

        $assets = Asset::dueForMaintenance($days)->with('location')->get();

        if ($assets->isEmpty()) {
            $this->info('No assets due for maintenance.');
            return;
        }

        $this->info("Found {$assets->count()} assets due for maintenance.");

        // En un escenario real, enviaríamos a los admins o encargados.
        // Por ahora, enviamos al primer admin que encontremos o a un email de config.
        $adminEmail = User::where('role', 'admin')->value('email') ?? config('mail.from.address');

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new MaintenanceAlert($assets));
            $this->info("Alert email sent to {$adminEmail}");
        } else {
            $this->warn('No admin email found to send alerts.');
        }
    }
}
