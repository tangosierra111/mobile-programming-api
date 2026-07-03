<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'meeting_number' => $this->meeting_number,
            'slug' => $this->slug,
            'title' => $this->title,
            'summary' => $this->summary,
            'accent_color' => $this->accent_color,
            'background_color' => $this->background_color,
            'status' => $this->status,
            'available_at' => $this->available_at?->toISOString(),
            'interactive_demo_key' => $this->interactive_demo_key,
            'sort_order' => $this->sort_order,
            'keywords' => $this->keywords->pluck('keyword')->values(),
            'content_blocks' => ContentBlockResource::collection($this->contentBlocks),
        ];
    }
}
