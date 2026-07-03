<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'study_program' => $this->study_program,
            'faculty' => $this->faculty,
            'lecturer_name' => $this->lecturer_name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];
    }
}
