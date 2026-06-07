<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'phone'             => 'nullable|string|max:20',
            'address'           => 'nullable|string|max:1000',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|integer|exists:products,id',
            'items.*.quantity'  => 'required|integer|min:1|max:100',
        ];
    }
}
