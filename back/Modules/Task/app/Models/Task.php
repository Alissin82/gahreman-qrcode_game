<?php

namespace Modules\Task\Models;

use App\Models\Mission;
use App\Models\ScoreTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Task\Database\Factories\TaskFactory;
use Modules\Task\Enum\TaskType;

class Task extends Model
{
    use HasFactory;

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }

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

    public function score(): MorphMany
    {
        return $this->morphMany(ScoreTeam::class, 'scorable');
    }
}
