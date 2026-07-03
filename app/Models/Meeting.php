<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    protected $fillable = [
        'course_id', 'meeting_number', 'slug', 'title', 'summary',
        'accent_color', 'background_color', 'status', 'available_at',
        'interactive_demo_key', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'meeting_number' => 'integer',
            'sort_order' => 'integer',
            'available_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(MeetingKeyword::class);
    }

    public function contentBlocks(): HasMany
    {
        return $this->hasMany(MeetingContentBlock::class)->orderBy('sort_order');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserMeetingProgress::class);
    }
}
