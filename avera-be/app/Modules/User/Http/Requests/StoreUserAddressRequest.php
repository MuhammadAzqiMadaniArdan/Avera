<?php

namespace App\Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserAddressRequest extends FormRequest
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
            'label' => 'required|in:home,work',
            'recipient_name' => "required|string|min:3|max:255|regex:/^[\p{L}\p{N}\s\-]+$/u",
            'recipient_phone' => 'required|string|min:8|max:16',

            'province_name' => 'required|string',
            'city_name' => 'required|string',
            'district_name' => 'required|string|min:3|max:255',
            'village_name' => 'required|string',

            'postal_code' => 'nullable|string|min:1|max:11',
            'address' => 'required|string|min:1|max:1000',
            'other' => 'nullable|string|min:1|max:255',

            'province_id' => 'required|integer|exists:provinces,id',
            'city_id' => 'required|integer|exists:cities,id',
            'district_id' => 'required|integer|exists:districts,id',
            'village_id' => 'required|integer',

            'is_default' => 'sometimes|boolean',
        ];
    }
}
