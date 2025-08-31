<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Task\Models\Task;

class Mission extends Model
{
    protected $fillable = [
        'action_id',
        'title',
        'score',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'mission_team');
    }
}
