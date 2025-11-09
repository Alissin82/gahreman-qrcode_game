<?php

namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskTeam extends Model
{
    protected $table = 'task_team';

    protected $fillable = ['team_id', 'task_id'];

}
