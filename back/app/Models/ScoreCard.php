<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreCard extends Model
{
    protected $fillable  = [
        'name',
        'score'
    ];
}
