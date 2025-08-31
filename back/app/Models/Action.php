<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Action extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'release',
        'region_id',
        'score'
    ];

    protected $casts = [
        'release' => 'datetime',
    ];

    protected $appends = [
        'meta',
        'estimated_time'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachment')->singleFile();
    }

    /**
     * @return MorphOne<Media,$this>
     */
    public function attachment(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'attachment');
    }

    public function getMetaAttribute(): array
    {
        $team = Auth::guard('team')->user();

        if ($team instanceof Team) {
            $actionMissionIds = $this->missions()->pluck('missions.id')->toArray();
            $teamMissionIds = $team->missions()->pluck('missions.id')->toArray();

            $completedCount = count(array_intersect($actionMissionIds, $teamMissionIds));

            return [
                'total' => count($actionMissionIds),
                'completed' => $completedCount,
            ];
        } else {
            return [
                'total' => 0,
                'completed' => 0,
            ];
        }
    }

    public function getEstimatedTimeAttribute(): int
    {
        return $this->missions()->withSum('tasks', 'duration')->get()->sum('tasks_sum_duration') ?? 0;
    }

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

    public function actionTeams(): HasMany
    {
        return $this->hasMany(ActionTeam::class);
    }
}
