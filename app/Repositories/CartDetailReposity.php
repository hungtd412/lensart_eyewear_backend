<?php

namespace App\Repositories;


use App\Models\CartDetail;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use App\Repositories\CartDetailReposityInterface;

class CartDetailReposity implements CartDetailReposityInterface
{
    public function getAllCartDetails($userId)
    {
        $cart = Cart::where('user_id', $userId)->first(); // Lấy giỏ hàng duy nhất

        if ($cart) {
            $cartDetails = CartDetail::where('cart_id', $cart->id)
                ->with([
                    'product' => function ($query) {
                        $query->select('id', 'name', 'price', 'status', 'brand_id', 'category_id')
                            ->with([
                                'category' => function ($q) {
                                    $q->select('id', 'name');
                                },
                                'brand' => function ($q) {
                                    $q->select('id', 'name');
                                },
                                'images' => function ($q) {
                                    $q->select('id', 'product_id', 'image_url');
                                }
                            ]);
                    },
                    'branch' => function ($query) {
                        $query->select('id', 'name', 'index', 'address');
                    }
                ])
                ->get(); // Lấy tất cả chi tiết giỏ hàng (dạng Collection)

            // Trả về dữ liệu dưới dạng mảng
            return $cartDetails->map(function ($cartDetail) {
                return [
                    'id' => $cartDetail->id,
                    'product_name' => $cartDetail->product->name ?? 'N/A',
                    'product_price' => $cartDetail->product->price ?? 0,
                    'brands_name' => $cartDetail->product->brand->name ?? 'N/A',
                    'category_name' => $cartDetail->product->category->name ?? 'N/A',
                    'color' => $cartDetail->color,
                    'quantity' => $cartDetail->quantity,
                    'image_url' => $cartDetail->product->images->first()->image_url ?? null,
                    'branches_name' => $cartDetail->branch->name ?? 'N/A',
                    'total_price' => $cartDetail->quantity * $cartDetail->product->price * ($cartDetail->branch->index ?? 1),
                ];
            });
        }

        return collect([]); // Trả về Collection rỗng nếu không tìm thấy giỏ hàng
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

    public function getOrCreateCart($userId)
    {
        return Cart::firstOrCreate(['user_id' => $userId]); // Tạo giỏ hàng nếu chưa tồn tại
    }

    public function addOrUpdateCartDetail($cartId, $productId, array $attributes)
    {
        // Tìm kiếm CartDetail hiện tại
        $cartDetail = CartDetail::where([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'branch_id' => $attributes['branch_id'],
            'color' => $attributes['color'],
        ])->first();

        if ($cartDetail) {
            // Nếu tồn tại, cộng thêm số lượng
            $attributes['quantity'] = $cartDetail->quantity + $attributes['quantity'];
            return $this->update($attributes, $cartDetail); // Sử dụng hàm update
        } else {
            // Nếu không tồn tại, tạo mới
            $newCartDetail = CartDetail::create([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'branch_id' => $attributes['branch_id'],
                'color' => $attributes['color'],
                'quantity' => $attributes['quantity'],
            ]);

            return $newCartDetail;
        }
    }
}
