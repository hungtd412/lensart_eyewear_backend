<?php

namespace App\Http\Requests;

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
            'cart_id' => 'required|integer|exists:carts,id',
            'product_id' => 'required|integer|exists:products,id',
            'branch_id' => 'required|integer|exists:branches,id',
            'color' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ];
    }
}
