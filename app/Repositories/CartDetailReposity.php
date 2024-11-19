<?php

namespace App\Repositories;


use App\Models\CartDetail;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ProductDetail;
use App\Repositories\CartDetailReposityInterface;

class CartDetailReposity implements CartDetailReposityInterface
{
    public function getAllCartDetails($userId)
    {
        // Lấy cart của user
        $cart = Cart::where('user_id', $userId)->first();

        // Kiểm tra nếu cart tồn tại, trả về các cart_details của cart
        if ($cart) {
            return CartDetail::where('cart_id', $cart->id)
                ->with([
                    'product' => function ($query) {
                        $query->select('id', 'name', 'price', 'status', 'brand_id', 'category_id') // Chọn các cột cần thiết
                            ->with([
                                'category' => function ($q) {
                                    $q->select('id', 'name'); // Chọn tên danh mục
                                },
                                'brand' => function ($q) {
                                    $q->select('id', 'name'); // Chọn tên thương hiệu
                                },
                                'images' => function ($q) {
                                    $q->select('id', 'product_id', 'image_url'); // Chọn ảnh sản phẩm
                                }
                            ]);
                    },
                    'branch' => function ($query) {
                        $query->select('id', 'name', 'index', 'address'); // Chọn chi nhánh
                    }
                ])
                ->get()
                ->map(function ($cartDetail) {
                    return [
                        'id' => $cartDetail->id,
                        'product_name' => $cartDetail->product->name ?? 'N/A',
                        'product_price' => $cartDetail->product->price ?? 0,
                        'brands_name' => $cartDetail->product->brand->name ?? 'N/A', // Lấy tên thương hiệu
                        'category_name' => $cartDetail->product->category->name ?? 'N/A', // Lấy tên danh mục
                        'color' => $cartDetail->color,
                        'quantity' => $cartDetail->quantity,
                        'image_url' => $cartDetail->product->images->first()->image_url ?? null, // Lấy ảnh đầu tiên
                        'branches_name' => $cartDetail->branch->name ?? 'N/A', // Lấy tên chi nhánh
                        'total_price' => $cartDetail->quantity * $cartDetail->product->price * ($cartDetail->branch->index ?? 1),
                    ];
                });
        }

        // Trả về collection rỗng nếu không tìm thấy cart
        return collect([]);
    }

    public function getCartByUserId($userId)
    {
        return Cart::where('user_id', $userId)->first();
    }

    public function getByIdAndUser($id, $userId)
    {
        return CartDetail::where('id', $id)
            ->whereHas('cart', function ($query) use ($userId) {
                $query->where('user_id', $userId); // Kiểm tra user_id của cart
            })
            ->first();
    }

    public function getCartByIdAndUser($cartId, $userId)
    {
        return Cart::where('id', $cartId)
            ->where('user_id', $userId)
            ->first();
    }

    public function store(array $cartDetail): CartDetail
    {
        // Kiểm tra xem có bản ghi nào trùng không
        $existingCartDetail = CartDetail::where('cart_id', $cartDetail['cart_id'])
            ->where('product_id', $cartDetail['product_id'])
            ->where('branch_id', $cartDetail['branch_id'])
            ->where('color', $cartDetail['color'])
            ->first();

        // Nếu đã tồn tại, cập nhật số lượng và tổng giá
        if ($existingCartDetail) {
            $existingCartDetail->quantity += $cartDetail['quantity'];
            $this->updateCartDetailTotalPrice($existingCartDetail);
            return $existingCartDetail;
        }

        // Nếu không tồn tại, tạo mới
        $newCartDetail = CartDetail::create($cartDetail);

        // Cập nhật `total_price` cho `CartDetail` vừa tạo
        $this->updateCartDetailTotalPrice($newCartDetail);

        return $newCartDetail;
    }

    public function updateCartDetailTotalPrice(CartDetail $cartDetail)
    {
        $product = $cartDetail->product;
        $branch = $cartDetail->branch;

        if ($product && $branch) {
            // Tính lại `total_price`
            $cartDetail->total_price = $cartDetail->quantity * $product->price * ($branch->index ?? 1);
            $cartDetail->save();
        }
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
