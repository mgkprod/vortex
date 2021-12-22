<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    use HasFactory;

    const TYPE_HTTP = 1;
    const TYPE_KEYWORD = 2;
    const TYPE_PING = 3;

    const TYPES = [
        self::TYPE_HTTP => 'HTTP(s)',
        self::TYPE_KEYWORD => 'Keyword',
        self::TYPE_PING => 'Ping',
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
        $counts = $this
            ->hasMany(Heartbeat::class)
            ->selectRaw('status, count(*) as count')
            ->whereBetween('created_at', [carbon()->subDays(7), carbon()])
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
    }
}
