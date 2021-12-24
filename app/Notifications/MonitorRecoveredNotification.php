<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

class MonitorRecoveredNotification extends MonitorNotification
{
    use Queueable;

    public function toArray($notifiable)
    {
        return array_merge(
            parent::toArray($notifiable),
            [
                'reason' => '',
                'text' => 'Monitor is UP: ' . $notifiable->name . '.'
                    . ' It was down for ' . $this->durationForHumans,
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
                        ['name' => 'Was down for', 'value' => $this->durationForHumans],
                    ],
                ],
            ]
        );
    }
}
