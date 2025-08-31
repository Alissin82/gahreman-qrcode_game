<?php

namespace Modules\Task\Models;

use App\Models\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Task\Enum\TaskType;

class Task extends Model
{
    protected $fillable = [
        'type',
        'options',
        'question',
        'answer',
        'order',
        'content',
        'text',
        'sort',
        'score',
        'duration',
        'need_review'
    ];

    protected function casts(): array
    {
        return [
            'type' => TaskType::class
        ];
    }


    public function mission(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }
}
