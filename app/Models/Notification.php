<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $casts = [
        'data' => 'json',
    ];

    public function target()
    {
        return $this->morphTo('notifiable');
    }
}
