<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_number' => $this->question_number,
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'options_json' => $this->options_json,
            'weight_percent' => $this->weight_percent,
            'max_time_minutes' => $this->max_time_minutes,
            'sort_order' => $this->sort_order,
        ];
    }
}
