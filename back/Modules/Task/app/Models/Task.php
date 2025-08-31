<?php

namespace Modules\Task\Models;

use App\Models\Mission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Task\Enum\TaskType;

class Task extends Model
{
    protected $fillable = [
        'mission_id',
        'taskable_type',
        'taskable_id',
        'type',
        'duration',
        'score',
        'order',
        'need_review'
    ];

    protected function casts(): array
    {
        return [
            'type' => TaskType::class,
            'need_review' => 'boolean'
        ];
    }

    public function mission(): BelongsTo
    {
        return $this->belongsTo(Mission::class);
    }

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }
}
