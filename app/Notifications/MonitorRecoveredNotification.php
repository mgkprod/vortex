<?php

namespace App\Notifications;

use App\Models\Heartbeat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MonitorRecoveredNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Heartbeat $latestUpHeartbeat,
    ) {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'status' => Heartbeat::STATUS_UP,
            'text' => 'Monitor is UP: ' . $notifiable->name . '.'
                . ' It was down for ' . $this->latestUpHeartbeat->created_at->diffForHumans(),
        ];
    }
}
