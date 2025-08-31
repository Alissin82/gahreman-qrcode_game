<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ScoreTeam extends Model
{
    /** @use HasFactory<\Database\Factories\ScoreTeamFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'score',
        'scorable_id',
        'scorable_type'
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function scorable(): MorphTo
    {
        return $this->morphTo();
    }
}
