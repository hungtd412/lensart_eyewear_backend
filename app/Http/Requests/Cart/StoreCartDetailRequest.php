<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;

class StoreCartDetailRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * Xác định xem người dùng có được ủy quyền để thực hiện yêu cầu này hay không.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Lấy các quy tắc xác thực áp dụng cho yêu cầu.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'cart_id' => 'required|integer|exists:carts,id',
            'product_id' => 'required|integer|exists:products,id',
            'branch_id' => 'required|integer|exists:branches,id',
            'color' => 'required|string|exists:product_details,color',
            'quantity' => 'required|integer|min:1'
        ];
    }
}
