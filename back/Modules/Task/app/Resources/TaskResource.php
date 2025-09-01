<?php

namespace Modules\Task\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Task\Enum\TaskType;
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
            'done_by_team' => $this->when(!is_null($this->done_by_team), $this->done_by_team),
            'locked_for_team' => $this->when(!is_null($this->locked_for_team),  $this->locked_for_team),
            'type_label' => $this->type->getLabel(),
            'duration' => $this->duration,
            'score' => $this->score,
            'order' => $this->order,
            'need_review' => $this->need_review,
            'created_at' => $this->created_at,
        ];
    }
}
