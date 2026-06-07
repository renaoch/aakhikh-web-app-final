<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:500',
            'starts_at'   => 'required|date',
            'ends_at'     => 'nullable|date|after_or_equal:starts_at',
            'image_url'   => 'nullable|url|max:500',
            'is_featured' => 'boolean',
            'is_recurring'=> 'boolean',
            'recurrence'  => 'nullable|in:weekly,monthly,yearly',
        ];
    }
}
