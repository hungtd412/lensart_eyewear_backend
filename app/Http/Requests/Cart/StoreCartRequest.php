<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;

class StoreCartRequest extends FormRequest
{
    use FailedValidationTrait;

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
            'user_id' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            // 'cart_details' => 'required|array',
            // 'cart_details.*.product_id' => 'required|integer|min:1',
            // 'cart_details.*.branch_id' => 'required|integer|min:1',
            // 'cart_details.*.color' => 'required|string|max:50',
            // 'cart_details.*.quantity' => 'required|integer|min:1',
            // 'cart_details.*.total_price' => 'required|numeric|min:0',
        ];
    }
}
