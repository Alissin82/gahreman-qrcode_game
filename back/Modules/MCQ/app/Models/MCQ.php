<?php

namespace Modules\MCQ\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Task\Models\Task;

class MCQ extends Model
{
    protected $fillable = ['question', 'answer', 'options'];

    protected function casts(): array
    {
        return [
            'options' => 'array'
        ];
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
