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

    public function toDiscord($notifiable)
    {
        return array_merge(
            parent::toDiscord($notifiable),
            [
                'embed' => [
                    'title' => 'Monitor is DOWN 😭😭',
                    'fields' => [
                        ['name' => 'Monitor', 'value' => $notifiable->name],
                    ],
                ],
            ]
        );
    }
}
