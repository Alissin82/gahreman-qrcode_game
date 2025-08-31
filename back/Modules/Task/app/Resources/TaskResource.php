<?php

namespace Modules\Task\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Task\Models\Task;

/**
 * @mixin Task
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mission_id' => $this->mission_id,
            'taskable' => $this->taskable,
            'type' => $this->type,
            'duration' => $this->duration,
            'score' => $this->score,
            'order' => $this->order,
            'need_review' => $this->need_review,
            'created_at' => $this->created_at,
        ];
    }
}
