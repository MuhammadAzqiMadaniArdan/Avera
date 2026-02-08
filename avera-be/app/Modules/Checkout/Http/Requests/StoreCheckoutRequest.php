<?php

namespace App\Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
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
            'carts' => 'required|array|min:1',
            'carts.*.id' => 'required|exists:cart_items,id',
            'carts.*.promo_id' => 'nullable|exists:promotions,id',
            'carts.*.user_voucher_id' => 'nullable|exists:user_vouchers,id',
        ];
    }
}
