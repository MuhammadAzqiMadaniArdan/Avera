<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromotionProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "discount_type" => "required|in:percentage,fixed",
            "discount_value" => [
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
                    if ($type === 'fixed' && $value >= 9999999999.99) {
                        $fail('Fixed discount must be less than 9999999999.99.');
                    }
                }
            ]
        ];
    }
}
