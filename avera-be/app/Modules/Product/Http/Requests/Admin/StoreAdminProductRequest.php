<?php

namespace App\Modules\Product\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminProductRequest extends FormRequest
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
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|max:255|regex:/^[a-zA-Z0-9\s.,-]+$/',
            'description' => 'nullable|string|min:3|max:1000',
            'price' => 'required|numeric|min:0.01|max_digits:12',
            'stock' => 'required|integer|min:1|max_digits:12',
            'weight' => 'required|integer|min:1|max_digits:12',
            'status' => 'required|in:draft,active,inactive',
            'age_rating' => 'required|in:all,13+,18+',
            'moderation_visibility' => 'required|string|min:3|max:1000',
            'views_count' => 'required|integer|min:0|max_digits:19',
            'sales_count' => 'required|integer|min:0|max_digits:19',
            'images' => 'required|array|max:5|min:0',
            'images.*' => 'required|string|exists:product_images,id',
        ];
    }
}
