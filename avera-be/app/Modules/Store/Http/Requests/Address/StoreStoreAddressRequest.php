<?php

namespace App\Modules\Store\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreAddressRequest extends FormRequest
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
     * @return array<string, \Illuxminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_name' => "sometimes|string|min:3|max:255|regex:/^[\p{L}\p{N}\s\-]+$/u",
            'phone_number' => 'sometimes|string|min:8|max:16',

            'province_name' => 'sometimes|string',
            'city_name' => 'sometimes|string',
            'district_name' => 'sometimes|string|min:3|max:255',
            'village_name' => 'sometimes|string',

            'postal_code' => 'sometimes|string|min:1|max:11',
            'address' => 'sometimes|string|min:1|max:1000',

            'province_id' => 'sometimes|integer|exists:provinces,id',
            'city_id' => 'sometimes|integer|exists:cities,id',
            'district_id' => 'sometimes|integer',
            'village_id' => 'sometimes|integer',

        ];
    }
}
