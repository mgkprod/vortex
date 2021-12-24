<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Contact extends Model
{
    use HasFactory, Notifiable;

    const TYPE_EMAIL = 1;
    const TYPE_SMS = 2;
    const TYPE_WEBHOOK = 3;
    const TYPE_DISCORD = 4;

    const TYPES = [
        // self::TYPE_EMAIL => 'Email', // not yet implemented
        // self::TYPE_SMS => 'SMS', // not yet implemented
        // self::TYPE_WEBHOOK => 'Webhook', // not yet implemented
        self::TYPE_DISCORD => 'Discord',
    ];

    protected $casts = [
        'configuration' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function monitors()
    {
        return $this->belongsToMany(Monitor::class);
    }
}
