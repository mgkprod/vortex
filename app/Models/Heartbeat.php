<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heartbeat extends Model
{
    use HasFactory;

    const STATUS_DOWN = 0;
    const STATUS_UP = 1;

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'data' => 'json',
    ];

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}
