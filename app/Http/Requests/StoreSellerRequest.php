<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSellerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address'=>'required|string|max:100',
            'phone'=>'required|string|max:15',
            'baio'=>'nullable|string',
            'image'=>'required|image|mimes:png,jpg,jpeg,gif|max:2048'
        ];
    }
}
