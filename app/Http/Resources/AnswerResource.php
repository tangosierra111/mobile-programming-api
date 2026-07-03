<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'attempt_id' => $this->attempt_id,
            'question_id' => $this->question_id,
            'answer_text' => $this->answer_text,
            'selected_option' => $this->selected_option,
            'file_url' => $this->file_url,
            'score' => $this->score,
            'lecturer_feedback' => $this->lecturer_feedback,
        ];
    }
}
