<?php

namespace App\Notifications;

use App\Channels\DiscordChannel;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContactTestNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        if ($notifiable->type == Contact::TYPE_DISCORD) {
            return [DiscordChannel::class];
        }
    }

    public function toArray($notifiable)
    {
        return [];
    }

    public function toDiscord($notifiable)
    {
        return [
            'content' => null,
            'embed' => [
                'title' => 'Webhook is working 😀🔥!',
            ],
            'type' => 'info',
        ];
    }
}
