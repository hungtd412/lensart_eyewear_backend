<?php

namespace App\Repositories;

use App\Models\Wishlist;
use App\Models\WishlistDetail;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\Auth;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function getByUserId($userId)
    {
        $wishlist = Wishlist::where('user_id', $userId)
            ->with([
                'details' => function ($query) {
                    $query->with(['product.category']);
                }
            ])
            ->first();

        if ($wishlist) {
            return [
                'wishlist_id' => $wishlist->id,
                'user_id' => $wishlist->user_id,
                'details' => $wishlist->details->map(function ($detail) {
                    return [
                        'wishlist_detail_id' => $detail->id,
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name,
                        'product_price' => $detail->product->price,
                        'category' => $detail->product->category->name ?? 'N/A'
                    ];
                })
            ];
        }

        return null;
    }

    public function store(array $data)
    {
        $wishlist = Wishlist::firstOrCreate(['user_id' => $data['user_id']]);
        WishlistDetail::create([
            'wishlist_id' => $wishlist->id,
            'product_id' => $data['product_id'],
        ]);
        return $wishlist;
    }

    public function delete($wishlistDetailId)
    {
        $wishlistDetail = WishlistDetail::find($wishlistDetailId);
        if ($wishlistDetail) {
            $wishlistDetail->delete();
        }
    }

    public function clearByUserId($userId)
    {
        // Tìm Wishlist theo `user_id`
        $wishlist = Wishlist::where('user_id', $userId)->first();

        // Nếu tìm thấy Wishlist thì xóa tất cả các `wishlist_details`
        if ($wishlist) {
            $wishlist->details()->delete(); // Xóa tất cả `wishlist_details`
        }
    }
    public function moveProductToCart($wishlistDetailId)
    {
        $userId = Auth::id();
        $wishlistDetail = WishlistDetail::find($wishlistDetailId);

        if (!$wishlistDetail || $wishlistDetail->wishlist->user_id !== $userId) {
            return false;
        }

        // Lấy chi tiết sản phẩm
        $productDetail = ProductDetail::where('product_id', $wishlistDetail->product_id)
            ->where('quantity', '>', 0)
            ->first();

        if (!$productDetail) {
            return false;
        }

        // Tìm hoặc tạo giỏ hàng cho user
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        $cartDetail = CartDetail::firstOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $wishlistDetail->product_id,
                'branch_id' => $productDetail->branch_id,
                'color' => $productDetail->color,
            ],
            [
                'quantity' => 1,
                'total_price' => 1 * $productDetail->price * $productDetail->branch->index,
            ]
        );

        // Nếu đã có sản phẩm trong giỏ hàng, tăng số lượng
        if (!$cartDetail->wasRecentlyCreated) {
            $cartDetail->quantity += 1;
            $this->updateCartDetailTotalPrice($cartDetail);
        }

        // Xóa sản phẩm khỏi wishlist
        $wishlistDetail->delete();

        return true;
    }

    public function moveAllToCart()
    {
        $userId = Auth::id();
        $wishlist = Wishlist::where('user_id', $userId)->first();

        if (!$wishlist) {
            return false;
        }

        $wishlistDetails = WishlistDetail::where('wishlist_id', $wishlist->id)->get();

        foreach ($wishlistDetails as $wishlistDetail) {
            $this->moveProductToCart($wishlistDetail->id);
        }

        return true;
    }


    private function updateCartDetailTotalPrice($cartDetail)
    {
        $product = $cartDetail->product;
        $branch = $cartDetail->branch;

        if ($product && $branch) {
            // Tính lại `total_price` cho `CartDetail`
            $cartDetail->total_price = $cartDetail->quantity * $product->price * $branch->index;
            $cartDetail->save();
        }
    }
}
