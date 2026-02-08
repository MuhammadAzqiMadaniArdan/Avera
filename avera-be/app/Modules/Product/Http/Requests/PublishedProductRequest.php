<?php

namespace App\Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublishedProductRequest extends FormRequest
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
            'description' => 'required|string|min:3|max:1000',
            'price' => 'required|numeric|min:0.01|max_digits:12',
            'stock' => 'required|integer|min:1|max_digits:12',
            'weight' => 'required|integer|min:1|max_digits:12',
            'age_rating' => 'required|in:all,13+,18+',
            'images' => 'required|array|max:5',
            'images.*' => 'required|string|exists:product_images,id',
        ];
    }
}
