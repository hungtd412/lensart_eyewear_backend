<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WishlistService;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    // Lấy danh sách wishlist của user
    public function index()
    {
        $wishlists = $this->wishlistService->getUserWishlist();
        return response()->json($wishlists);
    }

    // Thêm sản phẩm vào wishlist
    public function store(Request $request)
    {
        $data = $request->all();
        $wishlist = $this->wishlistService->addProductToWishlist($data);
        return response()->json(['message' => 'Product added to wishlist', 'wishlist' => $wishlist], 200);
    }

    // Xóa sản phẩm khỏi wishlist
    public function destroy($id)
    {
        $this->wishlistService->removeProductFromWishlist($id);
        return response()->json(['message' => 'Product removed from wishlist'], 200);
    }

    // Xóa tất cả sản phẩm trong wishlist của user
    public function clearWishlist()
    {
        $this->wishlistService->clearUserWishlist();
        return response()->json(['message' => 'Wishlist cleared'], 200);
    }

    // Chuyển một sản phẩm từ wishlist sang cart
    public function moveProductToCart($wishlistDetailId)
    {
        $result = $this->wishlistService->moveProductToCart($wishlistDetailId);
        return response()->json(['message' => $result ? 'Product moved to cart' : 'Failed to move product'], $result ? 200 : 400);
    }

    // Chuyển toàn bộ sản phẩm từ wishlist sang cart
    public function moveAllToCart()
    {
        $result = $this->wishlistService->moveAllToCart();
        return response()->json(['message' => 'All products moved to cart'], 200);
    }
}
