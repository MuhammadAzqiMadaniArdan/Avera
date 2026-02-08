<?php

namespace App\Modules\Voucher\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSellerVoucherRequest extends FormRequest
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
            "name" => "required|string|min:3|max:100|regex:/^[\pL\pN\s\-\.\']+$/u/",
            "code" => "required|string|unique:store_voucher,id|uppercase|min:3|max:100|regex:/^[\pL\pN\s\-\.\']+$/u/",
            "type" => "required|in:free_shipping,cashback,discount",
            "discount_type" => "required_if:type,discount|in:percentage,fixed",
            "discount_value" => [
                'required_if:type,discount',
                'numeric',
                function ($attr, $value, $fail) {
                    $type = $this->input('discount_type');

                    if ($type === 'percentage' && ($value <= 0 || $value > 100)) {
                        $fail('Discount percentage must be between 1 and 100.');
                    }
                    if ($type === 'fixed' && $value <= 0) {
                        $fail('Fixed discount must be greater than 0.');
                    }
                }
            ],
            "min_order_amount" => "required_if:type,discount|numeric|min:1|max_digits:9",
            "max_discount" => "required_if:type,discount|numeric|min:1|max:100",
            "total_quota" => "nullable|integer|min:1|max_digits:9",
            "usage_limit_per_user" => "required|numeric|min:1|max_digits:9",
            "is_claimable" => "required|boolean",
        ];
    }
}
