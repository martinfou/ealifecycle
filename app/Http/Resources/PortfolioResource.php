<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
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
            'description' => $this->description,
            'initial_capital' => $this->initial_capital,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'group_id' => $this->group_id,
            'owner' => $this->user->name,
            'group' => $this->group?->name,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'strategies' => StrategyResource::collection($this->whenLoaded('strategies')),
        ];
    }
}
