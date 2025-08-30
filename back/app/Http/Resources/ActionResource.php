<?php

namespace App\Http\Resources;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'started_by_team' => count($this->whenLoaded('actionTeams')) > 0,
            'created_at' => $this->created_at,
            'meta' => $this->meta ?? null,
        ];
    }
}
