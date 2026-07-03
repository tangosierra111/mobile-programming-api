<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:150'],
            'position' => ['nullable', 'string', 'max:100'],
            'profession' => ['nullable', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'about' => ['nullable', 'string'],
            'projects_count' => ['sometimes', 'integer', 'min:0'],
            'followers_count' => ['sometimes', 'integer', 'min:0'],
            'experience_years' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'linkedin_url' => ['nullable', 'url', 'max:2048'],
        ];
    }
}
