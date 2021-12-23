<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Monitor extends Model
{
    use HasFactory, Notifiable;

    const TYPE_HTTP = 1;
    const TYPE_KEYWORD = 2;
    const TYPE_PORT = 3;

    const TYPES = [
        self::TYPE_HTTP => 'HTTP(s)',
        self::TYPE_KEYWORD => 'Keyword',
        self::TYPE_PORT => 'Port',
    ];

    protected $casts = [
        'configuration' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function latestHeartbeat()
    {
        return $this->hasOne(Heartbeat::class)->latestOfMany('created_at');
    }

    public function heartbeats()
    {
        return $this->hasMany(Heartbeat::class)->orderByDesc('created_at');
    }

    public function getUptimeAttribute()
    {
        return $this->uptimes([7])[0];
    }

    public function uptimes(array $intervals)
    {
        return collect($intervals)->map(function ($interval) {
            $counts = $this
                ->hasMany(Heartbeat::class)
                ->selectRaw('status, count(*) as count')
                ->whereBetween('created_at', [carbon()->subDays($interval), carbon()])
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $counts[Heartbeat::STATUS_UP] ??= 0;
            $counts[Heartbeat::STATUS_DOWN] ??= 0;

            try {
                return round($counts[Heartbeat::STATUS_UP] / ($counts[Heartbeat::STATUS_UP] + $counts[Heartbeat::STATUS_DOWN]) * 100, 2);
            } catch (\Throwable $th) {
                return -1;
            }
        });
    }
}
