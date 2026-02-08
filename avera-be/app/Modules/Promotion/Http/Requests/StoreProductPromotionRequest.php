<?php

namespace App\Modules\Promotion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductPromotionRequest extends FormRequest
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
            "start_at" => "required|date",
            "end_at" => "required|date|after:start_at",
            "products" => "required|array|min:1",
            "products.*.ids" => "required|array|min:1",
            "products.*.ids.*" => "required|exists:products,id",
            "products.*.discount_type" => "required|in:percentage,fixed",
            "products.*.discount_value" => [
                'required',
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
            ]
        ];
    }
}
