<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Team extends Model implements AuthenticatableContract
{
    use Authenticatable, HasApiTokens;

    protected $appends = [
        'total_mission_score'
    ];

    protected $fillable = [
        'name',
        'color',
        'bio',
        'content',
        'score',
        'coin',
        'phone',
        'start'
    ];

    protected $casts = [
        'gender' => 'boolean',
        'start' => 'datetime'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(TeamAdmins::class, 'team_id');
    }
    public function users(): HasMany
    {
        return $this->hasMany(TeamUsers::class, 'team_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(ScoreMission::class, 'team_id');
    }

    /** @noinspection PhpUnused
     */
    public function getTotalMissionScoreAttribute(): float|int
    {
        return $this->scores()
            ->with('mission')
            ->get()
            ->sum(fn($score) => $score->mission ? $score->mission->score : 0);
    }

    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'action_team')->using(ActionTeam::class);
    }

    public function missions(): BelongsToMany
    {
        return $this->belongsToMany(Mission::class, 'mission_team');
    }
}
