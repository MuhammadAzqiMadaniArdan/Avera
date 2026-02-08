<?php

namespace App\Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserCategoryRequest extends FormRequest
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
            "name" => "required|string|unique:categories,name|regex:/^[\pL\-\/&,]+$/u|min:3|max:255",
            "description" => "required|string|min:3|max:1000",
            "default_age_rating" => "required|in:all,13+,18+",
            "parent_id" => "nullable|uuid|exists:categories,id",
        ];
    }
    public function messages()
    {
        return [
            "name:regex" => 'Name cannot contain the signs "<" and ">"'
        ];
    }
}
