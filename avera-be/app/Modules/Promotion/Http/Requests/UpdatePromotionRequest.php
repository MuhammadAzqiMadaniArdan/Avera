<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromotionRequest extends FormRequest
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
            "name" => "sometimes|string|min:3|max:100|regex:/^[\pL\pN\s\-\.\']+$/u/",
            "type" => "sometimes|in:product_discount,bundle,combo",
            "start_at" => "sometimes:type,date|date",
            "end_at" => "sometimes:type,date|date|after:start_at"
        ];
    }
}
