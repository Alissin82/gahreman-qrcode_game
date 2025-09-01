<?php

namespace Modules\MCQ\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Task\Models\Task;

class MCQ extends Model
{
    protected $table = 'm_c_qs';

    protected $fillable = ['question', 'answer', 'options'];

    protected $hidden = ['answer'];

    protected function casts(): array
    {
        return [
            'options' => 'array'
        ];
    }

    public function task(): MorphOne
    {
        return $this->morphOne(Task::class, 'taskable');
    }
}
