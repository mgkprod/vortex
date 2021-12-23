<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

class MonitorDownNotification extends MonitorNotification
{
    use Queueable;

    public function toArray($notifiable)
    {
        return array_merge(
            parent::toArray($notifiable),
            [
                'reason' => '',
                'text' => 'Monitor is DOWN: ' . $notifiable->name . '.',
            ]
        );
    }
}
