<?php

namespace App\Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|max:255|regex:/^[a-zA-Z0-9\s.,-]+$/',
            'description' => 'sometimes|string|min:3|max:1000',
            'price' => 'required|numeric|min:0.01|max_digits:12',
            'stock' => 'sometimes|integer|min:1|max_digits:12',
            'weight' => 'sometimes|integer|min:1|max_digits:12',
            'age_rating' => 'sometimes|in:all,13+,18+',
            'images' => 'sometimes|array|max:5|min:0',
            'images.*' => 'sometimes|string|exists:product_images,id',
        ];
    }
}
