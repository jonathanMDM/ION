<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserNotification;
use App\Models\User;
use App\Models\Asset;

class CreateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test notifications for the current user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', $this->ask('Enter your email'))->first();

        if (!$user) {
            $this->error('User not found!');
            return 1;
        }

        // Get a random asset for testing
        $asset = Asset::where('company_id', $user->company_id)->first();

        if (!$asset) {
            $this->error('No assets found for this company!');
            return 1;
        }

        // Create test notifications
        $notifications = [
            [
                'type' => 'low_stock_alert',
                'title' => '⚠️ Alerta de Stock Bajo',
                'message' => "El activo '{$asset->name}' tiene stock bajo. Cantidad actual: 2, Mínimo: 5",
                'data' => [
                    'asset_id' => $asset->id,
                    'asset_name' => $asset->name,
                    'current_quantity' => 2,
                    'minimum_quantity' => 5,
                    'action_url' => route('assets.show', $asset->id),
                ],
            ],
            [
                'type' => 'asset_assigned',
                'title' => '📦 Activo Asignado',
                'message' => "Se te ha asignado el activo '{$asset->name}'",
                'data' => [
                    'asset_id' => $asset->id,
                    'asset_name' => $asset->name,
                    'action_url' => route('assets.show', $asset->id),
                ],
            ],
            [
                'type' => 'maintenance_reminder',
                'title' => '🔧 Recordatorio de Mantenimiento',
                'message' => "El activo '{$asset->name}' requiere mantenimiento próximamente",
                'data' => [
                    'asset_id' => $asset->id,
                    'asset_name' => $asset->name,
                    'action_url' => route('assets.show', $asset->id),
                ],
            ],
        ];

        foreach ($notifications as $notificationData) {
            UserNotification::create(array_merge($notificationData, ['user_id' => $user->id]));
        }

        $this->info("✅ Created 3 test notifications for {$user->name} ({$user->email})");
        $this->info("Check the notification bell icon in the app!");

        return 0;
    }
}
