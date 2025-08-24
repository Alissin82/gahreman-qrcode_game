<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreMission extends Model
{
    protected $fillable = [
        'team_id',
        'mission_id',
    ];
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class, 'mission_id');
    }
}
