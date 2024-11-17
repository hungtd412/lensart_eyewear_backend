<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request): JsonResponse
    {
        $carts = $this->cartService->getAllCarts();
        return response()->json(['data' => $carts], 200);
    }
}
