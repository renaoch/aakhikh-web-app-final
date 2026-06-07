<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:subscribers,email',
            'name'  => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already subscribed.',
        ];
    }
}
