<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'location',
        'position',
        'profession',
        'phone_number',
        'about',
        'photo_url',
        'projects_count',
        'followers_count',
        'experience_years',
        'linkedin_url',
    ];

    protected function casts(): array
    {
        return [
            'projects_count' => 'integer',
            'followers_count' => 'integer',
            'experience_years' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
