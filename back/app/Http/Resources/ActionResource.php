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
            'missions' => $this->whenLoaded('missions'),
            'release' => $this->whenLoaded('region'),
            'created_at' => $this->created_at,
        ];
    }
}
