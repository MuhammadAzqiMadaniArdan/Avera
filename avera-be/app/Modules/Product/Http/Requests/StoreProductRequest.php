<?php

namespace App\Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'description' => 'nullable|string|max:1000|regex:/^[a-zA-Z0-9\s.,-]+$/', 
        ];
    }
}
