<?php

namespace App\Models;

use App\Enums\ActionStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ActionTeam extends Pivot
{
    protected $fillable = [
        'action_id',
        'team_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ActionStatus::class
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }
}
