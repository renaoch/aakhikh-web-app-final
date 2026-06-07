<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDailyBreadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'scripture'   => 'required|string|max:255',
            'author'      => 'nullable|string|max:255',
            'image_url'   => 'nullable|url|max:500',
            'published_at'=> 'nullable|date',
            'send_email'  => 'boolean',
        ];
    }
}
