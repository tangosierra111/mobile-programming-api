<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course' => new CourseResource($this->course),
            'exam_type' => $this->exam_type,
            'title' => $this->title,
            'room' => $this->room,
            'class_code' => $this->class_code,
            'exam_date' => $this->exam_date?->toDateString(),
            'start_time' => $this->start_time,
            'duration_minutes' => $this->duration_minutes,
            'exam_kind' => $this->exam_kind,
            'learning_outcome' => $this->learning_outcome,
            'instructions' => $this->instructions,
            'status' => $this->status,
            'questions' => ExamQuestionResource::collection($this->questions),
        ];
    }
}
