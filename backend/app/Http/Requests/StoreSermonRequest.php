<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSermonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'           => 'required|string|max:255',
            'speaker'         => 'required|string|max:255',
            'youtube_url'     => 'nullable|url|max:500',
            'youtube_id'      => 'nullable|string|max:50',
            'description'     => 'nullable|string',
            'scripture'       => 'nullable|string|max:255',
            'series'          => 'nullable|string|max:255',
            'thumbnail_url'   => 'nullable|url|max:500',
            'preached_at'     => 'nullable|date',
            'duration_seconds'=> 'nullable|integer|min:0',
            'tags'            => 'nullable|array',
            'tags.*'          => 'string|max:50',
        ];
    }
}
