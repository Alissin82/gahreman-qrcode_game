<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    protected $fillable = [
        'name',
        'title'
    ];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'game_teams');
    }
}
