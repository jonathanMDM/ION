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
    protected $signature = 'test:notifications {email? : Email of the user to create notifications for}';

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
        // Try to get user by email argument first
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User with email '{$email}' not found!");
                return 1;
            }
        } else {
            // Try to get authenticated user, otherwise get first active user
            $user = auth()->user();
            
            if (!$user) {
                $user = User::where('is_active', true)->first();
            }

            if (!$user) {
                $this->error('No active users found!');
                return 1;
            }
        }

        $this->info("Creating test notifications for: {$user->name} ({$user->email})");

        // Get a random asset for testing (optional)
        $asset = Asset::where('company_id', $user->company_id)->first();
        
        $assetName = $asset ? $asset->name : 'Laptop Dell XPS 15';
        $assetId = $asset ? $asset->id : 1;
        $actionUrl = $asset ? route('assets.show', $asset->id) : route('dashboard');

        // Create test notifications
        $notifications = [
            [
                'type' => 'low_stock_alert',
                'title' => '⚠️ Alerta de Stock Bajo',
                'message' => "El activo '{$assetName}' tiene stock bajo. Cantidad actual: 2, Mínimo: 5",
                'data' => [
                    'asset_id' => $assetId,
                    'asset_name' => $assetName,
                    'current_quantity' => 2,
                    'minimum_quantity' => 5,
                    'action_url' => $actionUrl,
                ],
            ],
            [
                'type' => 'asset_assigned',
                'title' => '📦 Activo Asignado',
                'message' => "Se te ha asignado el activo '{$assetName}'",
                'data' => [
                    'asset_id' => $assetId,
                    'asset_name' => $assetName,
                    'action_url' => $actionUrl,
                ],
            ],
            [
                'type' => 'maintenance_reminder',
                'title' => '🔧 Recordatorio de Mantenimiento',
                'message' => "El activo '{$assetName}' requiere mantenimiento próximamente",
                'data' => [
                    'asset_id' => $assetId,
                    'asset_name' => $assetName,
                    'action_url' => $actionUrl,
                ],
            ],
        ];

        foreach ($notifications as $notificationData) {
            UserNotification::create(array_merge($notificationData, ['user_id' => $user->id]));
        }

        $this->info("✅ Created 3 test notifications for {$user->name} ({$user->email})");
        $this->info("🔔 Check the notification bell icon in the app!");
        $this->info("📧 User email: {$user->email}");

        return 0;
    }
}
