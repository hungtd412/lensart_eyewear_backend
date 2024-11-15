<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(Request $request)
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Bạn cần đăng nhập để thêm vào giỏ hàng'], 401);
        }

        $data = $request->validate([
            'product_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'color' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $data['user_id'] = $user->id;

        $cart = $this->cartService->addToCart($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng',
            'cart' => $cart
        ], 200);
    }

    public function getCart()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Bạn cần đăng nhập để xem giỏ hàng'], 401);
        }

        $cart = $this->cartService->getCart($user->id);

        return response()->json([
            'status' => 'success',
            'cart' => $cart
        ], 200);
    }
}
