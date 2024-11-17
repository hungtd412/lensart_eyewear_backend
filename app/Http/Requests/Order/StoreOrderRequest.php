<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;

class StoreOrderRequest extends FormRequest {
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
            'user_id' => 'required|integer|min:1',
            'date' => 'required|date_format:Y/m/d H:i:s',
            'branch_id' => 'required|integer|min:1',
            'address' => 'required|string|min:2|max:100',
            'note' => 'required|string|min:2|max:500',
            'coupon_code' => 'string|min:2|max:50',
            'cart_details' => 'required|array',
            'cart_details.*' => 'integer|min:1',
        ];
    }
}
