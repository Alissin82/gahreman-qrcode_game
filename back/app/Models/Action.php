<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class);
    }

    public function dependency(): HasMany
    {
        return $this->hasMany(ActionDependency::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'action_team');
    }
}
