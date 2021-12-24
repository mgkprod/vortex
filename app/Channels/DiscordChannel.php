<?php

namespace App\Channels;

use App\Helpers\Discord;
use App\Models\Contact;
use App\Models\Monitor;
use Illuminate\Notifications\Notification;

class DiscordChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toDiscord($notifiable);

        if (get_class($notifiable) == Monitor::class) {
            $contacts = $notifiable->contacts()->where('type', Contact::TYPE_DISCORD)->get();
        } elseif (get_class($notifiable) == Contact::class) {
            $contacts = [$notifiable];
        }

        foreach ($contacts as $contact) {
            (new Discord($contact->configuration['discord_webhook']))->webhook(
                $message['content'],
                $message['embed'],
                $message['type']
            );
        }
    }
}
