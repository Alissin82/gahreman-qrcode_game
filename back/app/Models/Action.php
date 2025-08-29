<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = [
        'name',
        'release',
        'region_id'
    ];

    protected $casts = [
        'release' => 'datetime',
    ];

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

    public function dependency()
    {
        return $this->hasMany(ActionDependency::class);
    }

    public function region()
    {
        return $this->belongsTo(\App\Models\Region::class, 'region_id');
    }
}
