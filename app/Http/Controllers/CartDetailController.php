<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreCartDetailRequest;
use App\Services\CartDetailService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartDetailController extends Controller
{
    protected $cartDetailService;

    public function __construct(CartDetailService $cartDetailService)
    {
        $this->cartDetailService = $cartDetailService;
    }

    public function index(Request $request): JsonResponse
    {
        $cartDetails = $this->cartDetailService->getAllCartDetails();
        return response()->json(['data' => $cartDetails], 200);
    }

    public function store(StoreCartDetailRequest $request)
    {
        return $this->cartDetailService->store($request->validated());
    }

    public function update(StoreCartDetailRequest $request, $id)
    {
        return $this->cartDetailService->update($request->validated(), $id);
    }

    public function delete($cartDetailId)
    {
        // Gọi service để xóa mục giỏ hàng
        return $this->cartDetailService->delete($cartDetailId);
    }

    public function clearCart($cartId)
    {
        // Gọi service để xóa toàn bộ giỏ hàng
        return $this->cartDetailService->clearCart($cartId);
    }

    // Tính tổng tiền và áp dụng mã giảm giá
    public function calculateTotalWithCoupon(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        $couponCode = $request->input('coupon_code');

        $result = $this->cartDetailService->calculateTotalWithCoupon($selectedIds, $couponCode);

        return response()->json($result, 200);
    }
}
