<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'email' => $this->user?->email ?? $request->user()?->email,
            'location' => $this->location,
            'position' => $this->position,
            'profession' => $this->profession,
            'phone_number' => $this->phone_number,
            'about' => $this->about,
            'photo_url' => $this->photo_url,
            'projects_count' => $this->projects_count,
            'followers_count' => $this->followers_count,
            'experience_years' => $this->experience_years,
            'linkedin_url' => $this->linkedin_url,
        ];
    }
}
