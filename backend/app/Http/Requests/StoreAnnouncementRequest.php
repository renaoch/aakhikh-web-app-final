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
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        return [
            'title' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:255'],
            'body' => [$isUpdate ? 'sometimes' : 'required', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'published_at' => [$isUpdate ? 'sometimes' : 'required', 'date'],
            'expires_at' => ['sometimes', 'nullable', 'date', 'after:today'],
            'created_by' => ['sometimes', 'nullable', 'exists:users,id'],
        ];
    }
}