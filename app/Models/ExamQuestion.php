<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id', 'question_number', 'question_text', 'question_type',
        'options_json', 'weight_percent', 'max_time_minutes', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'question_number' => 'integer',
            'options_json' => 'array',
            'weight_percent' => 'float',
            'max_time_minutes' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class, 'question_id');
    }
}
