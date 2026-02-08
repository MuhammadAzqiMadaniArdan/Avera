<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminUserRequest extends FormRequest
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
            'role' => 'sometimes|in:user,seller',
            'status' => 'sometimes|in:active,warning,suspended,banned',
            'violitation_count' => 'sometimes|integer|min:1|max_digits:9',
            'suspended_until' => 'sometimes|date',
        ];
    }
}
