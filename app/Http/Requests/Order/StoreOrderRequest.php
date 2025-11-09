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
            'branch_id' => 'required|exists:branches,id',
            'address' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
            'coupon_id' => 'nullable|exists:coupons,id',
            'payment_method' => 'required|in:Chuyển khoản,Tiền mặt,Crypto',
            'shipping_fee' => 'required|numeric|min:0',

            'order_details' => 'required|array|min:1',
            'order_details.*.product_id' => 'required|exists:products,id',
            'order_details.*.color' => 'nullable|string|max:255',
            'order_details.*.quantity' => 'required|integer|min:1',
            'order_details.*.total_price' => 'required|numeric|min:0',
        ];
    }
}
