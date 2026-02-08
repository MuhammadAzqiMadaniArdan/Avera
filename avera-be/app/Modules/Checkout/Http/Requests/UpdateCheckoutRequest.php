<?php

namespace App\Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCheckoutRequest extends FormRequest
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
            'user_address_id' => 'sometimes|exists:user_addresses,id',
            'shipment_id'     => 'required_with:store_id|exists:checkout_shipments,id',
            'store_id'    => 'required_with:shipment_id|exists:stores,id',
            'payment_method' => 'sometimes|in:cod,midtrans',
        ];
    }
}
