<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Models\Company;
use App\Models\User;
use App\Models\UserNotification;

class CheckLowStockAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for assets with low stock and send notifications to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for low stock alerts...');

        // Get all companies with low stock alerts enabled
        $companies = Company::where('low_stock_alerts_enabled', true)
            ->where('status', 'active')
            ->get();

        if ($companies->isEmpty()) {
            $this->info('No companies have low stock alerts enabled.');
            return 0;
        }

        $totalAlerts = 0;

        foreach ($companies as $company) {
            $this->info("Checking company: {$company->name}");

            // Get assets with low stock for this company
            $lowStockAssets = Asset::where('company_id', $company->id)
                ->lowStock()
                ->get();

            if ($lowStockAssets->isEmpty()) {
                $this->info("  No low stock assets found.");
                continue;
            }

            $this->warn("  Found {$lowStockAssets->count()} assets with low stock!");

            // Get users from this company who have low stock notifications enabled
            $users = User::where('company_id', $company->id)
                ->where('is_active', true)
                ->get()
                ->filter(function ($user) {
                    return isset($user->preferences['notifications']['low_stock']) 
                        && $user->preferences['notifications']['low_stock'] === true;
                });

            if ($users->isEmpty()) {
                $this->info("  No users have low stock notifications enabled.");
                continue;
            }

            // Send notification to each user
            foreach ($users as $user) {
                foreach ($lowStockAssets as $asset) {
                    // Check if we already sent a notification for this asset today
                    $existingNotification = UserNotification::where('user_id', $user->id)
                        ->where('type', 'low_stock_alert')
                        ->where('data->asset_id', $asset->id)
                        ->whereDate('created_at', today())
                        ->exists();

                    if ($existingNotification) {
                        continue; // Skip if already notified today
                    }

                    UserNotification::create([
                        'user_id' => $user->id,
                        'type' => 'low_stock_alert',
                        'title' => '⚠️ Alerta de Stock Bajo',
                        'message' => "El activo '{$asset->name}' tiene stock bajo. Cantidad actual: {$asset->quantity}, Mínimo: {$asset->minimum_quantity}",
                        'data' => [
                            'asset_id' => $asset->id,
                            'asset_name' => $asset->name,
                            'current_quantity' => $asset->quantity,
                            'minimum_quantity' => $asset->minimum_quantity,
                            'action_url' => route('assets.show', $asset->id),
                        ],
                    ]);

                    $totalAlerts++;
                }
            }
        }

        $this->info("✅ Process completed! Sent {$totalAlerts} low stock alert(s).");
        return 0;
    }
}
