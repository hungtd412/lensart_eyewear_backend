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
            'coupon' => $coupon
        ], 200);
    }

    public function getAll() {
        $coupons = $this->couponRepository->getAll();

        return response()->json([
            'status' => 'success',
            'coupons' => $coupons
        ], 200);
    }

    public function getById($id) {
        $coupon = $this->couponRepository->getById($id);

        if ($coupon === null) {
            return response()->json([
                'message' => 'can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'coupon' => $coupon,
        ], 200);
    }

    public function getByCode($code) {
        $coupon = $this->couponRepository->getByCode($code);

        if ($coupon === null) {
            return response()->json([
                'message' => 'can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'coupon' => $coupon,
        ], 200);
    }

    public function update($data, $id) {
        $coupon = $this->couponRepository->getById($id);

        $this->couponRepository->update($data, $coupon);

        return response()->json([
            'message' => 'success',
            'coupon' => $coupon
        ], 200);
    }

    public function switchStatus($id) {
        $coupon = $this->couponRepository->getById($id);

        $this->couponRepository->switchStatus($coupon);

        return response()->json([
            'message' => 'success',
            'coupon' => $coupon
        ], 200);
    }
}
