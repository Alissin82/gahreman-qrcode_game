<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamCoin extends Model
{
    protected $fillable = [
        'team_id',
        'coin',
        'comment'
    ];


    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
