<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSellerRequest extends FormRequest
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
            'address'=>'sometimes|string|max:100',
            'phone'=>'sometimes|string|max:15',
            'baio'=>'sometimes|nullable|string',
            'image'=>'sometimes|image|mimes:png,jpg,jpeg,gif|max:2048'
        ];
    }
}
