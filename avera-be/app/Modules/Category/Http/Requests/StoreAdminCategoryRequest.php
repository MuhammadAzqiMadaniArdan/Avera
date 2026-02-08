<?php

namespace App\Modules\Category\Http\Requests;

use App\Modules\Category\Enum\CategoryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminCategoryRequest extends FormRequest
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
            "name" => "required|string|unique:categories,name|regex:/^[\pL\pN\s\-\.\']+$/u/|min:3|max:255",
            "status" => ["required", Rule::in(CategoryStatus::values())],
            "description" => "nullable|string|min:3|max:1000",
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
