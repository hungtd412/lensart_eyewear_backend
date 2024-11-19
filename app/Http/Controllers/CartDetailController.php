<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreCartDetailRequest;
use App\Services\CartDetailService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class CartDetailController extends Controller
{
    protected $cartDetailService;

    public function __construct(CartDetailService $cartDetailService)
    {
        $this->cartDetailService = $cartDetailService;
    }

    public function index(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $cartDetails = $this->cartDetailService->getAllCartDetails($userId);
        return response()->json(['data' => $cartDetails], 200);
    }

    public function store(StoreCartDetailRequest $request)
    {
        $userId = auth()->id(); // Lấy user_id
        $data = $request->validated();
        $data['user_id'] = $userId; // Thêm user_id vào dữ liệu trước khi gọi service
        return $this->cartDetailService->store($data);
    }

    public function update(StoreCartDetailRequest $request, $id)
    {
        $userId = auth()->id(); // Lấy user_id
        return $this->cartDetailService->update($request->validated(), $id, $userId);
    }

    public function delete($cartDetailId)
    {
        $userId = auth()->id(); // Lấy user_id
        return $this->cartDetailService->delete($cartDetailId, $userId);
    }

    public function clearCart($cartId)
    {
        $userId = auth()->id(); // Lấy user_id
        return $this->cartDetailService->clearCart($cartId, $userId);
    }

    // Tính tổng tiền và áp dụng mã giảm giá
    public function calculateTotalWithCoupon(Request $request)
    {
        $userId = auth()->id(); // Lấy user_id
        $selectedIds = $request->input('selected_ids', []);
        $couponCode = $request->input('coupon_code');
        $result = $this->cartDetailService->calculateTotalWithCoupon($userId, $selectedIds, $couponCode);
        return response()->json($result, 200);
    }
}