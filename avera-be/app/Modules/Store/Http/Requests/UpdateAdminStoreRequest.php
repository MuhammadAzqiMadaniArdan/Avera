<?php

namespace App\Modules\Store\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'name' => 'sometimes|string|min:3|max:50|regex:/^[\pL\pN\s\-\.]+$/u',
            'description' => 'nullable|string|min:3|max:1000',
            'status' => 'sometimes|in:active,suspended',
            'slug' => 'sometimes|string|min:3|max:30',
            'rating' => 'sometimes|number|min:0.01|max:5',
            'verification_status' => 'sometimes|in:unverified,pending,verified,rejected,suspended',
            'verified_by' => 'required_if:verification_status,verified|exists:users,id',
        ];
    }
}
