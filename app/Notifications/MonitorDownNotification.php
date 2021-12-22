<?php

namespace App\Notifications;

use App\Models\Heartbeat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MonitorDownNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Heartbeat $heartbeat,
    ) {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // TODO: Add reason.

        return [
            'status' => Heartbeat::STATUS_DOWN,
            'text' => 'Monitor is DOWN: ' . $notifiable->name,
        ];
    }
}
