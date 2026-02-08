<?php

namespace App\Modules\Category\Http\Requests;

use App\Modules\Category\Enum\CategoryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
            "name" => "sometimes|string|unique:categories,name|regex:/^[\pL\pN\s\-\.\']+$/u|min:3|max:255",
            "status" => ["sometimes|string|min:3|max:255",Rule::in(CategoryStatus::values())],
            "default_age_rating" => "required|in:all,13+,18+",
            "description" => "nullable|string|min:3|max:1000",
            "parent_id" => "nullable|uuid|exists:categories,id",
        ];
    }
    public function messages()
    {
        return [
            "name:regex" => 'Name cannot contain the signs "<" and ">" '
        ];
    }
}
