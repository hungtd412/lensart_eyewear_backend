<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;

class GetOrderByStatusRequest extends FormRequest {
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
            'status' => 'required|in:Đang xử lý,Đã xử lý và sẵn sàng giao hàng,Đang giao hàng,Đã giao,Đã hủy,Chưa thanh toán,Đã thanh toán',
        ];
    }
}
