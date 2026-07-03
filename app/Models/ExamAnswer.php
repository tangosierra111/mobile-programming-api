<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamAnswer extends Model
{
    protected $fillable = [
        'attempt_id', 'question_id', 'answer_text', 'selected_option',
        'file_url', 'score', 'lecturer_feedback',
    ];

    protected function casts(): array
    {
        return ['score' => 'float'];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }
}
