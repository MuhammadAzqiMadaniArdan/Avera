<?php

namespace App\Modules\Banner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
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
            'title' => "required|string|min:3|max:255|regex:/^[\pL\pN\s\-\.\']+$/u/",
            'link_type' => "nullable|in:store,product,category,admin",
            'link_id' => "nullable",
            'link_url' => "nullable|string|min:3|max:255|active_url",
            'priorty' => "required|integer|min:1|max:100",
            'created_by' => "required|exists:user,id|min:1|max:100",
            'image' => "required|exists:images,id"
        ];
    }
}
