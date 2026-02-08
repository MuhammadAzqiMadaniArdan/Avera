<?php

namespace App\Modules\Store\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerStoreRequest extends FormRequest
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
            'link_type' => "nullable|in:product,internal",
            'link_id' => "required_if:link_type,product",
            'link_url' => "required_if:link_type,internal|string|max:255|active_url",
            'priority' => "required|integer|min:1|max:100",
            'start_at' => "nullable|date",
            'end_at' => "nullable|date|after:start_at",
            'images' => "required|array|max:5|min:1",
            'images.*' => "required|image|mimes:png,jpg,jpeg|max:2048"
        ];  
    }
}
