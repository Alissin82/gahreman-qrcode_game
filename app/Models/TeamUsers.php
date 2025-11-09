<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamUsers extends Model
{
    //
    protected $fillable = [
        'team_id',
        'name',
        'family',
        'national_code',
        'glevel',
        'school',
        'reagon',
        'city',
        'province',
        'student_code',
        'basij_code',
        'average',
        'phone',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
