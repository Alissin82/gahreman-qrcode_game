<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $appends = ['total_mission_score'];
    protected $fillable = [
        'name',
        'color',
        'score',
    ];


    public function scores()
    {
        return $this->hasMany(ScoreMission::class, 'team_id');
    }

    public function getTotalMissionScoreAttribute(): float|int
    {
        return $this->scores()
            ->with('mission') // رابطه mission
            ->get() // میگیریم همه scoreها
            ->sum(fn($score) => $score->mission ? $score->mission->score : 0);
    }
}
