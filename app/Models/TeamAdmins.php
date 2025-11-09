<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamAdmins extends Model
{
    //
    protected $fillable = [
        'team_id',
        'name',
        'family',
        'gender',
        'start',
        'national_code',
        'phone',
        'history',
        'description',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
