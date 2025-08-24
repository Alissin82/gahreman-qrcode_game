<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mission extends Model
{
    protected $fillable = [
        'action_id',
        'title',
        'score',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'action_id');
    }
}
