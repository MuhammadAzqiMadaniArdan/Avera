<?php

namespace App\Modules\Product\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminProductRequest extends FormRequest
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
            'store_id' => 'sometimes|exists:stores,id',
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|min:3|max:255|regex:/^[a-zA-Z0-9\s.,-]+$/',
            'description' => 'sometimes|string|min:3|max:1000',
            'price' => 'sometimes|numeric|min:0.01|max_digits:12',
            'stock' => 'sometimes|integer|min:1|max_digits:12',
            'weight' => 'required|integer|min:1|max_digits:12',
            'status' => 'sometimes|in:draft,active,inactive',
            'age_rating' => 'sometimes|in:all,13+,18+',
            'moderation_visibility' => 'sometimes|string|min:3|max:1000',
            'views_count' => 'sometimes|integer|min:0|max_digits:19',
            'sales_count' => 'sometimes|integer|min:0|max_digits:19',
            'images' => 'sometimes|array|max:5|min:0',
            'images.*' => 'sometimes|string|exists:product_images,id',
        ];
    }
}
