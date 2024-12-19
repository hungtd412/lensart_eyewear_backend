<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;

class StoreCategoryRequest extends FormRequest {
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
            'status' => 'in:inactive,active'
        ];
    }
}
