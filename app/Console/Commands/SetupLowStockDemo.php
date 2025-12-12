<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Asset;
use App\Models\UserNotification;

class SetupLowStockDemo extends Command
{
    protected $signature = 'demo:low-stock {email}';
    protected $description = 'Setup low stock demo for a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User not found!");
            return 1;
        }

        // Enable alerts for company
        $company = $user->company;
        $company->low_stock_alerts_enabled = true;
        $company->save();
        $this->info("✅ Enabled low stock alerts for: {$company->name}");

        // Enable user preference
        $preferences = $user->preferences ?? [];
        $preferences['notifications']['low_stock'] = true;
        $user->preferences = $preferences;
        $user->save();
        $this->info("✅ Enabled low stock notifications for user");

        // Get or create an asset with low stock
        $asset = Asset::where('company_id', $company->id)->first();
        
        if ($asset) {
            $asset->minimum_quantity = 10;
            $asset->quantity = 3;
            $asset->save();
            $this->info("✅ Set asset '{$asset->name}' to low stock (quantity: 3, minimum: 10)");

            // Create notification
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

            $this->info("✅ Created low stock notification");
            $this->info("🔔 Click the notification to go to: " . route('assets.show', $asset->id));
        } else {
            $this->warn("No assets found for this company");
        }

        return 0;
    }
}
