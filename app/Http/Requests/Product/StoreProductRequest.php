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
            // 'color' => 'string|max:50',
            'shape_id' => 'nullable|integer|min:1',
            'material_id' => 'nullable|integer|min:1',
            'gender' => 'nullable|in:male,female,unisex',
            'price' => 'required|min:0|numeric',
            'offer_price' => 'nullable|min:0|numeric',
            'features' => 'nullable|array',
            'features.*' => 'nullable|integer|min:1',
            'status' => 'nullable|in:inactive,active'
        ];
    }
}
