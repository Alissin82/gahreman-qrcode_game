<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $fillable = [
        'title',
        'content',
        'sms',
        'app',
        'release',
    ];
    protected $casts = [
        'release' => 'datetime',
        'sms' => 'boolean',
        'app' => 'boolean',
    ];
    public function notify_teams()
    {
        return $this->hasMany(NotifyTeam::class);
    }
}
