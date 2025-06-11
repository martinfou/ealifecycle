<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StrategyResource extends JsonResource
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
            'symbols_traded' => $this->symbols_traded,
            'magic_number' => $this->magic_number,
            'status' => $this->status->name,
            'date_in_status' => $this->date_in_status->toIso8601String(),
            'owner' => $this->user->name,
            'group' => $this->group?->name,
            'timeframes' => $this->timeframes->pluck('name'),
            'primary_timeframe' => $this->primaryTimeframe()?->name,
            'has_source_code' => !is_null($this->source_code_path),
            'source_code_filename' => $this->source_code_original_filename,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
