<?php

namespace Modules\MCQ\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\MCQ\Database\Factories\MCQTeamFactory;

class MCQTeam extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): MCQTeamFactory
    // {
    //     // return MCQTeamFactory::new();
    // }
}
