<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = ['name','region_id'];
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

    public function region()
    {
        return $this->belongsTo(\App\Models\Region::class, 'region_id');
    }
}
