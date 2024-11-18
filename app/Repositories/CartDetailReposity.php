<?php

namespace App\Repositories;


use App\Models\CartDetail;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Support\Collection;
use App\Repositories\CartDetailReposityInterface;

class CartDetailReposity implements CartDetailReposityInterface
{
    public function getAllCartDetails(): Collection
    {
        return CartDetail::all();
    }

    // public function store(array $cartDetail): CartDetail {
    //     return CartDetail::create($cartDetail);
    // }

    public function store(array $cartDetail): CartDetail
    {
        // Tạo `CartDetail`
        $newCartDetail = CartDetail::create($cartDetail);

        // Cập nhật `total_price` cho `CartDetail` vừa tạo
        $this->updateCartDetailTotalPrice($newCartDetail);

        return $newCartDetail;
    }


    public function getById($id)
    {
        return CartDetail::find($id);
    }

    public function update(array $data, $cartDetail)
    {
        // Cập nhật số lượng cho `cartDetail`
        $cartDetail->quantity = $data['quantity'] ?? $cartDetail->quantity;
        $cartDetail->save();

        // Cập nhật lại `total_price` cho `cartDetail`
        $this->updateCartDetailTotalPrice($cartDetail);

        return $cartDetail;
    }

    private function updateCartDetailTotalPrice($cartDetail)
    {
        $product = $cartDetail->product;
        $branch = $cartDetail->branch;

        if ($product && $branch) {
            // Tính lại `total_price`
            $cartDetail->total_price = $cartDetail->quantity * $product->price * $branch->index;
            $cartDetail->save();
        }
    }

    // Xóa một mục trong giỏ hàng
    public function delete($cartDetailId)
    {
        $cartDetail = CartDetail::find($cartDetailId);
        if ($cartDetail) {
            $cartDetail->delete();
        }
    }

    public function clearCart($cartId)
    {
        CartDetail::where('cart_id', $cartId)->delete();
    }

    /**
     * Tính tổng tiền cho các sản phẩm được tick và áp dụng mã giảm giá nếu có
     */
    public function calculateTotalWithCoupon(array $selectedCartDetailIds, $couponCode = null)
    {
        // Nếu không có sản phẩm nào được chọn, trả về tổng tiền là 0
        if (empty($selectedCartDetailIds)) {
            return [
                'total_price' => 0,
                'discount' => 0,
                'final_price' => 0,
            ];
        }

        // Tính tổng tiền các sản phẩm được chọn
        $totalPrice = $this->calculateSelectedProductsTotal($selectedCartDetailIds);

        // Lấy mã giảm giá nếu có
        $discount = $this->applyCouponDiscount($couponCode);

        // Tính giá cuối cùng sau khi áp dụng giảm giá
        $finalPrice = $this->calculateFinalPrice($totalPrice, $discount);

        return [
            'total_price' => $totalPrice,
            'discount' => $discount,
            'final_price' => $finalPrice,
        ];
    }

    private function calculateSelectedProductsTotal(array $selectedCartDetailIds)
    {
        return CartDetail::whereIn('id', $selectedCartDetailIds)
            ->with(['product', 'branch'])
            ->get()
            ->sum(function ($cartDetail) {
                $product = $cartDetail->product;
                $branch = $cartDetail->branch;

                // Kiểm tra nếu sản phẩm và chi nhánh tồn tại
                if ($product && $branch) {
                    return $cartDetail->quantity * $product->price * $branch->index;
                }
                return 0;
            });
    }

    private function applyCouponDiscount($couponCode)
    {
        if (!$couponCode) {
            return 0;
        }

        $coupon = Coupon::where('code', $couponCode)
            ->where('status', 'active')
            ->where('quantity', '>', 0)
            ->first();

        // Trả về giá trị giảm giá nếu tìm thấy mã hợp lệ
        return $coupon ? $coupon->discount_price : 0;
    }

    private function calculateFinalPrice($totalPrice, $discount)
    {
        // Đảm bảo tổng giá cuối cùng không âm
        return max($totalPrice - $discount, 0);
    }
}
