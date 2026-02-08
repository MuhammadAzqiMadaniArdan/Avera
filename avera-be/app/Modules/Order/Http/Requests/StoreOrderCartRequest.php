<?php

namespace App\Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderCartRequest extends FormRequest
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
            'checkout_id' => 'required|array|min:1',
            'shipment.*.courier' => 'required|string|min:1|max:100',
            'shipment.*.recipient_name' => 'required|string|min:1|max:100',
            'shipment.*.recipient_phone' => 'required|string|min:1|max:100',
            'shipment.*.recipient_address' => 'required|string|min:1|max:100',
            'shipment.*.courier' => 'required|string|min:1|max:100',
            'shipment.*.courier' => 'required|string|min:1|max:100',
            'shipment.*.courier' => 'required|string|min:1|max:100',
        ];
    }
}
