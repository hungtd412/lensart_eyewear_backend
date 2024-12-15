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
                        $query->select('id', 'name', 'price', 'offer_price', 'status', 'brand_id', 'category_id') // Thêm offer_price
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
                $product = $cartDetail->product;
                $branch = $cartDetail->branch;

                $productPrice = $product ? $this->getEffectivePrice($product) : 0; // Sử dụng getEffectivePrice
                $branchIndex = $branch->index ?? 1;

                return [
                    'id' => $cartDetail->id,
                    'product_name' => $product->name ?? 'N/A',
                    'product_price' => $productPrice,
                    'brands_name' => $product->brand->name ?? 'N/A',
                    'category_name' => $product->category->name ?? 'N/A',
                    'color' => $cartDetail->color,
                    'quantity' => $cartDetail->quantity,
                    'image_url' => $product->images->first()->image_url ?? null,
                    'branches_name' => $branch->name ?? 'N/A',
                    'total_price' => $cartDetail->quantity * $productPrice * $branchIndex, // Sử dụng giá hiệu quả
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

    private function getEffectivePrice($product)
    {
        return $product->offer_price !== null ? $product->offer_price : $product->price;
    }



    public function store(array $cartDetail): ?CartDetail
    {
        // Lấy product_detail tương ứng
        $productDetail = DB::table('product_details')
            ->where('product_id', $cartDetail['product_id'])
            ->where('branch_id', $cartDetail['branch_id'])
            ->where('color', $cartDetail['color'])
            ->where('status', 'active')
            ->first();

        if (!$productDetail) {
            throw new \Exception('Chi tiết sản phẩm không tồn tại hoặc không hoạt động');
        }

        // Kiểm tra trạng thái sản phẩm
        $product = DB::table('products')
            ->select('id', 'offer_price', 'price', 'status') // Thêm 'offer_price' và 'price' vào truy vấn
            ->where('id', $cartDetail['product_id'])
            ->where('status', 'active')
            ->first();


        if (!$product) {
            throw new \Exception('Sản phẩm không tồn tại hoặc không hoạt động');
        }

        // Sử dụng giá từ hàm getEffectivePrice
        $price = $this->getEffectivePrice($product);

        // Kiểm tra số lượng trong kho
        if ($productDetail->quantity < $cartDetail['quantity']) {
            throw new \Exception('Số lượng trong kho không đủ');
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingCartDetail = CartDetail::where('cart_id', $cartDetail['cart_id'])
            ->where('product_id', $cartDetail['product_id'])
            ->where('branch_id', $cartDetail['branch_id'])
            ->where('color', $cartDetail['color'])
            ->first();

        if ($existingCartDetail) {
            // Cập nhật số lượng
            $newQuantity = $existingCartDetail->quantity + $cartDetail['quantity'];

            if ($newQuantity > $productDetail->quantity) {
                throw new \Exception('Số lượng trong giỏ hàng vượt quá số lượng trong kho');
            }

            $cartDetail['quantity'] = $newQuantity;
            $cartDetail['total_price'] = $cartDetail['quantity'] * $price;

            return $this->update($cartDetail, $existingCartDetail);
        }

        // Nếu không tồn tại trong giỏ hàng, tạo mới
        $cartDetail['total_price'] = $cartDetail['quantity'] * $price;

        $newCartDetail = CartDetail::create($cartDetail);

        // Cập nhật lại `total_price`
        $this->updateCartDetailTotalPrice($newCartDetail);

        return $newCartDetail;
    }



    public function updateCartDetailTotalPrice(CartDetail $cartDetail)
    {
        $product = $cartDetail->product;
        $branch = $cartDetail->branch;

        if ($product && $branch) {
            // Sử dụng hàm getEffectivePrice để lấy giá chính xác
            $price = $this->getEffectivePrice($product);

            // Tính lại `total_price`
            $cartDetail->total_price = $cartDetail->quantity * $price * ($branch->index ?? 1);

            // Lưu lại thay đổi
            $cartDetail->save();
        }
    }


    public function getById($id)
    {
        return CartDetail::find($id);
    }

    public function update(array $data, $cartDetail)
    {
        $productDetail = DB::table('product_details')
            ->where('product_id', $cartDetail->product_id)
            ->where('branch_id', $cartDetail->branch_id)
            ->where('color', $cartDetail->color)
            ->where('status', 'active')
            ->first();

        if (!$productDetail || $productDetail->quantity < $data['quantity']) {
            throw new \Exception('Số lượng trong kho không đủ');
        }

        $cartDetail->quantity = $data['quantity'];
        $cartDetail->save();

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

                if ($product && $branch) {
                    $price = $this->getEffectivePrice($product);
                    return $cartDetail->quantity * $price * $branch->index;
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
        // Lấy product_detail tương ứng
        $productDetail = DB::table('product_details')
            ->where('product_id', $productId)
            ->where('branch_id', $attributes['branch_id'])
            ->where('color', $attributes['color'])
            ->where('status', 'active')
            ->first();

        if (!$productDetail || $productDetail->quantity < $attributes['quantity']) {
            throw new \Exception('Số lượng trong kho không đủ');
        }

        $cartDetail = CartDetail::where([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'branch_id' => $attributes['branch_id'],
            'color' => $attributes['color'],
        ])->first();

        if ($cartDetail) {
            $newQuantity = $cartDetail->quantity + $attributes['quantity'];

            if ($newQuantity > $productDetail->quantity) {
                throw new \Exception('Số lượng trong giỏ hàng vượt quá số lượng trong kho');
            }

            $attributes['quantity'] = $newQuantity;
            return $this->update($attributes, $cartDetail);
        }

        return CartDetail::create([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'branch_id' => $attributes['branch_id'],
            'color' => $attributes['color'],
            'quantity' => $attributes['quantity'],
        ]);
    }

    public function calculateTotalQuantity($userId)
    {
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            return CartDetail::where('cart_id', $cart->id)->count();
        }

        return 0;
    }
}
