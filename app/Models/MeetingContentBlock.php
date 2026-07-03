<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingContentBlock extends Model
{
    protected $fillable = [
        'meeting_id', 'block_key', 'block_type', 'title', 'content_json',
        'sort_order', 'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'content_json' => 'array',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
}
