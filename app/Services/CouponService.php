<?php

namespace App\Services;

use App\Repositories\CouponRepositoryInterface;

class CouponService {
    protected $couponRepository;

    public function __construct(CouponRepositoryInterface $couponRepository) {
        $this->couponRepository = $couponRepository;
    }

    public function store($data) {
        $coupon = $this->couponRepository->store($data);

        return response()->json([
            'status' => 'success',
            'data' => $coupon
        ], 200);
    }

    public function getAll() {
        $coupons = $this->couponRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $coupons
        ], 200);
    }

    public function getAllActive() {
        $coupons = $this->couponRepository->getAllActive();

        return response()->json([
            'status' => 'success',
            'data' => $coupons
        ], 200);
    }

    public function getById($id) {
        $coupon = $this->couponRepository->getById($id);

        if ($coupon === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $coupon,
        ], 200);
    }

    public function getByCode($code) {
        $coupon = $this->couponRepository->getByCode($code);

        if ($coupon === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $coupon,
        ], 200);
    }

    public function decrementCouponQuantityByOneAndReturn($id) {
        $coupon = $this->couponRepository->getById($id);

        $this->decrementCouponQuantityByOne($coupon);

        if ($coupon === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $coupon,
        ], 200);
    }

    public function decrementCouponQuantityByOne(&$coupon) {
        $this->couponRepository->decrementCouponQuantityByOne($coupon);
    }

    public function update($data, $id) {
        $coupon = $this->couponRepository->getById($id);

        $this->couponRepository->update($data, $coupon);

        return response()->json([
            'message' => 'success',
            'data' => $coupon
        ], 200);
    }

    public function switchStatus($id) {
        $coupon = $this->couponRepository->getById($id);

        $this->couponRepository->switchStatus($coupon);

        return response()->json([
            'message' => 'success',
            'data' => $coupon
        ], 200);
    }
}
