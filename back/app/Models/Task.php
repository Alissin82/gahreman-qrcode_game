<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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


    public function mission(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'action_id');
    }
}
