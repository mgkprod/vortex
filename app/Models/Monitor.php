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

    public function heartbeats()
    {
        return $this->hasMany(Heartbeat::class);
    }
}
