<?php

namespace App\Notifications;

use App\Models\Heartbeat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MonitorUpNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'status' => Heartbeat::STATUS_UP,
            'text' => 'Monitor is UP: ' . $notifiable->name,
        ];
    }
}
