<?php

namespace App\Modules\User\Http\Requests;

use App\Helpers\AuthHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => 'sometimes|string|min:3|max:255',
            'username' => ['sometimes|string|min:3|max:30',Rule::unique('users','username')->ignore(AuthHelper::uuid($this),'identity_core_id')],
            'gender' => 'sometimes|in:male,female,other',
            'phone_number' => 'sometimes|regex:/^\+?[0-9]{10,15}$/',
        ];
    }
}
