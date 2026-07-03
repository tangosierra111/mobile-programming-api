<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_id' => $this->exam_id,
            'user_id' => $this->user_id,
            'attempt_number' => $this->attempt_number,
            'status' => $this->status,
            'score' => $this->score,
            'started_at' => $this->started_at?->toISOString(),
            'submitted_at' => $this->submitted_at?->toISOString(),
            'graded_at' => $this->graded_at?->toISOString(),
        ];
    }
}
