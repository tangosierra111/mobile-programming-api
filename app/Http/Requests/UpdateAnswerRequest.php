<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answer_text' => ['nullable', 'string'],
            'selected_option' => ['nullable', 'string', 'max:191'],
            'file_url' => ['nullable', 'url', 'max:2048'],
        ];
    }
}
