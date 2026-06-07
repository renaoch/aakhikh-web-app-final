<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'image_url'  => 'nullable|url|max:500',
            'is_urgent'  => 'boolean',
            'expires_at' => 'nullable|date|after:today',
            'send_email' => 'boolean',
        ];
    }
}
