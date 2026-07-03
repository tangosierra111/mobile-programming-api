<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'code', 'name', 'study_program', 'faculty', 'lecturer_name',
        'description', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class)->orderBy('sort_order');
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class)->orderBy('exam_date');
    }
}
