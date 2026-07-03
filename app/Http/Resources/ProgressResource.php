<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'meeting_id' => $this->meeting_id,
            'status' => $this->status,
            'progress_percent' => $this->progress_percent,
            'started_at' => $this->started_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
            'last_opened_at' => $this->last_opened_at?->toISOString(),
        ];
    }
}
