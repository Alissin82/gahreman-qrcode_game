<?php

namespace App\Http\Resources;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
            'missions' => MissionResource::collection($this->whenLoaded('missions')),
            'region' => new RegionResource($this->whenLoaded('region')),
            'started_by_team' => $this->relationLoaded('actionTeams') && $this->actionTeams->count() > 0,
            'icon' => new MediaResource($this->whenLoaded('icon')),
            'attachment' => new MediaResource($this->whenLoaded('attachment')),
            'created_at' => $this->created_at,
            'meta' => $this->meta ?? null,
            'estimated_time' => $this->estimated_time ?? 0,
        ];
    }
}
