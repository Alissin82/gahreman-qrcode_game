<?php

namespace App\Http\Resources;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Task\Resources\TaskResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin Action
 */
class ActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'region_id' => $this->region_id,
            'region' => new RegionResource($this->whenLoaded('region')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'team_tasks_completed_count' => $this->action_teams_count,
            'tasks_count' => $this->tasks_count,
            'started_by_team' => $this->relationLoaded('actionTeams') && $this->actionTeams->count() > 0,
            'icon' => new MediaResource($this->whenLoaded('icon')),
            'attachment' => new MediaResource($this->whenLoaded('attachment')),
            'created_at' => $this->created_at,
            'estimated_time' => $this->estimated_time,
        ];
    }
}
