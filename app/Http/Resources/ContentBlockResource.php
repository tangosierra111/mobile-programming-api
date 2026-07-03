<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentBlockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'meeting_id' => $this->meeting_id,
            'block_key' => $this->block_key,
            'block_type' => $this->block_type,
            'title' => $this->title,
            'content_json' => $this->content_json,
            'sort_order' => $this->sort_order,
            'is_visible' => $this->is_visible,
        ];
    }
}
