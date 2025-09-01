<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Modules\FileUpload\Models\FileUpload;
use Modules\MCQ\Models\MCQ;
use Modules\MCQ\Models\MCQTeam;
use Modules\Task\Models\Task;
use Str;

class Team extends Model implements AuthenticatableContract
{
    use Authenticatable, HasApiTokens;

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

    protected static function booted(): void
    {
        static::creating(function ($team) {
            do {
                $hash = Str::random();
            } while (Team::where('hash', $hash)->exists());

            $team->hash = $hash;
        });
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(TeamAdmins::class, 'team_id');
    }
    public function users(): HasMany
    {
        return $this->hasMany(TeamUsers::class, 'team_id');
    }

    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'action_team')->using(ActionTeam::class);
    }

    public function coins(): BelongsToMany
    {
        return $this->belongsToMany(Coin::class, 'coin_team');
    }

    public function MCQs(): BelongsToMany
    {
        return $this->belongsToMany(Mcq::class, 'm_c_q_team')
            ->using(McqTeam::class);
    }

    public function fileUploads(): BelongsToMany
    {
        return $this->belongsToMany(FileUpload::class, 'file_upload_team');
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_team');
    }

    public function scoreTeams(): HasMany
    {
        return $this->hasMany(ScoreTeam::class, 'team_id');
    }
}
