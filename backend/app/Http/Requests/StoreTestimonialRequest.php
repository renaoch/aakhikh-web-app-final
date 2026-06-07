<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'content'   => 'required|string|max:2000',
            'image_url' => 'nullable|url|max:500',
            'is_featured'=> 'boolean',
        ];
    }
}
