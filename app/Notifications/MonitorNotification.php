<?php

namespace App\Notifications;

use App\Channels\DiscordChannel;
use App\Models\Contact;
use App\Models\Heartbeat;
use Carbon\CarbonInterval;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MonitorNotification extends Notification
{
    use Queueable;

    public ?int $duration = null;
    public ?string $durationForHumans = null;

    public function __construct(
        public Heartbeat $heartbeat,
    ) {
        $inverseHeartbeat = $heartbeat
            ->monitor
            ->heartbeats()
            ->where('id', '!=', $heartbeat->id)
            ->where('status', $heartbeat->status)
            ->take(1)
            ->first();

        if ($inverseHeartbeat) {
            $this->duration = $inverseHeartbeat->created_at->diffInSeconds();
            $this->durationForHumans = CarbonInterval::create(0)->add('seconds', $this->duration)->cascade()->forHumans();
        }
    }

    public function via($notifiable)
    {
        $via = ['database'];

        $contacts = $notifiable->contacts;

        if ($contacts->where('type', Contact::TYPE_DISCORD)->count()) {
            $via[] = DiscordChannel::class;
        }

        return $via;
    }

    public function toArray($notifiable)
    {
        return [
            'status' => $this->heartbeat->status,
            'duration' => $this->duration,
        ];
    }

    public function toDiscord($notifiable)
    {
        return [
            'content' => null,
            'type' => $this->heartbeat->status ? 'success' : 'error',
        ];
    }
}
