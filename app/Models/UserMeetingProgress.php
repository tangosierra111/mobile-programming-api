<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMeetingProgress extends Model
{
    protected $table = 'user_meeting_progress';

    protected $fillable = [
        'user_id', 'meeting_id', 'status', 'progress_percent', 'started_at',
        'completed_at', 'last_opened_at',
    ];

    protected function casts(): array
    {
        return [
            'progress_percent' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'last_opened_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
}
