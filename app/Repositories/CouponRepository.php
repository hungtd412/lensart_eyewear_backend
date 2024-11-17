<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepository implements CouponRepositoryInterface {
    public function store(array $coupon): Coupon {
        return Coupon::create($coupon);
    }

    public function getAll() {
        return Coupon::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return Coupon::find($id)->where('status', 'active')->where('quantity', '>', 0)->first();
    }

    public function getByCode($code) {
        return Coupon::where('code', $code)->where('status', 'active')->where('quantity', '>', 0)->first();
    }

    public function decrementCouponQuantityByOne($coupon) {
        $coupon->quantity -= 1;
        $coupon->save();
    }

    public function update(array $data, $coupon) {
        $coupon->update($data);
    }

    public function switchStatus($coupon) {
        $coupon->status = $coupon->status == 'active' ? 'inactive' : 'active';
        $coupon->save();
    }
}
