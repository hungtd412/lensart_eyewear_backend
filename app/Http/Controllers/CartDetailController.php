<?php

namespace App\Http\Controllers;

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
}
