<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;

class StoreProductDetailRequest extends FormRequest {
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
            'product_id' => 'required|integer|min:1',
            'branch_id' => 'required|integer|min:1',
            'color' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
            'status' => 'in:active,inactive',
        ];
    }
}
