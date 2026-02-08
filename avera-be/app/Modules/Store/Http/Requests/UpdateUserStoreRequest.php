<?php

namespace App\Modules\Store\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserStoreRequest extends FormRequest
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
            'name' => 'sometimes|string|min:3|max:50|regex:/^[\pL\pN\s\-]+$/u',
            'description' => 'sometimes|string|min:3|max:1000',
        ];
    }
}
