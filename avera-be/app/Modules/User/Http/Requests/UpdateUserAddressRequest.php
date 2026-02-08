<?php

namespace App\Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAddressRequest extends FormRequest
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
            'label' => 'sometimes|in:home,work',
            'recipient_name' => "sometimes|string|min:3|max:255|regex:/^[\p{L}\p{N}\s\-]+$/u",
            'recipient_phone' => 'sometimes|string|min:8|max:16',

            'province_name' => 'sometimes|string',
            'city_name' => 'sometimes|string',
            'district_name' => 'sometimes|string|min:3|max:255',
            'village_name' => 'sometimes|string',

            'postal_code' => 'sometimes|string|min:1|max:11',
            'address' => 'sometimes|string|min:1|max:1000',
            'other' => 'sometimes|string|min:1|max:255',

            'province_id' => 'sometimes|integer|exists:provinces,id',
            'city_id' => 'sometimes|integer|exists:cities,id',
            'district_id' => 'sometimes|integer',
            'village_id' => 'sometimes|integer',

            'is_default' => 'sometimes|boolean',
        ];
    }
}
