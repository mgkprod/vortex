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
}
