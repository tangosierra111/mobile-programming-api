<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'course_id', 'exam_type', 'title', 'room', 'class_code', 'exam_date',
        'start_time', 'duration_minutes', 'exam_kind', 'learning_outcome',
        'instructions', 'status', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'duration_minutes' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class)->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
