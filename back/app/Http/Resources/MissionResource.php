<?php

namespace App\Http\Resources;

use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Task\Resources\TaskResource;

/**
 * @mixin Mission
 */
class MissionResource extends JsonResource
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
            'title' => $this->title,
            'score' => $this->score,
            'action_id' => $this->action_id,
            'action' => $this->whenLoaded('action'),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'created_at' => $this->created_at,
        ];
    }
}
