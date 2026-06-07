<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'title'     => 'required|string|max:255',
            'bio'       => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
            'order'     => 'nullable|integer|min:0',
            'email'     => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ];
    }
}
