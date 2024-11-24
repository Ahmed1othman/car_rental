<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' =>'required|string|max:255',
            'email' =>'required|string|email|max:255',
            'phone' =>'required|string|max:255',
            'message' =>'required|string|max:1000',
            'subject' =>'required|string|max:255',
        ];
    }
}
