<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

class MonitorUpNotification extends MonitorNotification
{
    use Queueable;

    public function toArray($notifiable)
    {
        return array_merge(
            parent::toArray($notifiable),
            [
                'reason' => '',
                'text' => 'Monitor is UP: ' . $notifiable->name . '.',
            ]
        );
    }

    public function toDiscord($notifiable)
    {
        return array_merge(
            parent::toDiscord($notifiable),
            [
                'embed' => [
                    'title' => 'Monitor is UP 🎉🎉',
                    'fields' => [
                        ['name' => 'Monitor', 'value' => $notifiable->name],
                    ],
                ],
            ]
        );
    }
}
