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
        'bio',
        'profile',
        'score',
        'coin'
    ];

    protected $casts = [
        'gender' => 'boolean',
    ];
    public function scores()
    {
        return $this->hasMany(ScoreMission::class, 'team_id');
    }

    public function getTotalMissionScoreAttribute(): float|int
    {
        return $this->scores()
            ->with('mission')
            ->get()
            ->sum(fn($score) => $score->mission ? $score->mission->score : 0);
    }
}
