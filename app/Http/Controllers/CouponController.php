<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreCouponRequest;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller {
    protected $couponService;

    public function __construct(CouponService $couponService) {
        $this->couponService = $couponService;
    }

    public function store(StoreCouponRequest $request) {
        return $this->couponService->store($request->validated());
    }

    public function index() {
        return $this->couponService->getAll();
    }

    public function indexActive() {
        return $this->couponService->getAllActive();
    }

    public function getById($id) {
        return $this->couponService->getById($id);
    }

    public function getByCode(Request $request) {
        $code = $request->query('code');

        if (!$code) {
            return response()->json([
                'message' => 'Missing code parameter'
            ], 400);
        }

        return $this->couponService->getByCode($code);
    }

    public function update(StoreCouponRequest $request, $id) {
        return $this->couponService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->couponService->switchStatus($id);
    }
}
