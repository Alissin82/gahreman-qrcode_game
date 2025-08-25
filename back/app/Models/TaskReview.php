<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskReview extends Model
{
    protected $fillable = ['task_id', 'team_id', 'attachment', 'score', 'comment'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
