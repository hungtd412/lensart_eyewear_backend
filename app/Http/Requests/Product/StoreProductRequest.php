<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;

class StoreProductRequest extends FormRequest {
    use FailedValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'name' => 'required|string|min:2|max:100',
            'description' => 'nullable|string|max:500',
            'brand_id' => 'required|integer|min:1',
            'category_id' => 'required|integer|min:1',
            'color' => 'required|string|max:50',
            'shape_id' => 'required|integer|min:1',
            'material_id' => 'required|integer|min:1',
            'gender' => 'required|in:male,female,unisex',
            'created_time' => 'required|date_format:Y/m/d H:i:s',
            'features' => 'required|array',
            'features.*' => 'integer|min:1',
        ];
    }
}
