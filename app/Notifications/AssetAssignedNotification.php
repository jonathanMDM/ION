<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\AssetAssignment;

class AssetAssignedNotification extends Notification
{
    use Queueable;

    public $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct(AssetAssignment $assignment)
    {
        $this->assignment = $assignment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nuevo Activo Asignado',
            'message' => "Se ha asignado el activo {$this->assignment->asset->name} a {$this->assignment->employee->full_name}.",
            'action_url' => route('assets.show', $this->assignment->asset_id),
            'icon' => 'fas fa-box-open',
            'color' => 'blue'
        ];
    }
}
