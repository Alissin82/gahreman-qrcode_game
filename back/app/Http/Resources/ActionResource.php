<?php

namespace App\Http\Resources;

use App\Models\Action;
use App\Models\ActionTeam;
use App\Models\Team;
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
        $team = \Auth::guard('team')->user() instanceof Team ? \Auth::guard('team')->user() : null;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'region_id' => $this->region_id,
            'action_team_for' => $this->when($this->relationLoaded('actionTeams') && $team && !is_null($this->actionTeamFor($team->id)), [
                'status' => $this->actionTeamFor($team->id)?->status,
                'status_label' => $this->actionTeamFor($team->id)?->status->la,
                'completed_task_count' => $this->actionTeamFor($team->id)?->team?->tasks->count(),
            ]),
            'region' => new RegionResource($this->whenLoaded('region')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'tasks_count' => $this->tasks_count,
            'icon' => new MediaResource($this->whenLoaded('icon')),
            'attachment' => new MediaResource($this->whenLoaded('attachment')),
            'created_at' => $this->created_at,
            'estimated_time' => $this->estimated_time,
        ];
    }
}
