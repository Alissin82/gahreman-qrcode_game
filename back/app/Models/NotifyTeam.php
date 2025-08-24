<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifyTeam extends Model
{
    protected $fillable =  [
        'notify_id',
        'team_id'
    ];
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function notify(): BelongsTo
    {
        return $this->belongsTo(Notify::class, 'notify_id');
    }
}
