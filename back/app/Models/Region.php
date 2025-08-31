<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = ['name', 'x', 'y', 'lockable', 'locked'];

    public function actions(): HasMany
    {
        return $this->hasMany(Action::class);
    }
}
