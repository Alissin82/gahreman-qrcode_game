<?php

namespace Modules\MCQ\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Task\Models\Task;

class MCQ extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
