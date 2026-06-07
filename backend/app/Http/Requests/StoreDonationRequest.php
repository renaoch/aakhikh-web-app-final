<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount'   => 'required|numeric|min:1|max:1000000',
            'category' => 'required|in:tithe,mission,general',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min'      => 'Minimum donation amount is ₹1.',
            'category.in'     => 'Category must be tithe, mission, or general.',
        ];
    }
}
