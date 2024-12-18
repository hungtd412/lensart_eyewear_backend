<?php

namespace App\Repositories;

interface CouponRepositoryInterface {
    public function store(array $coupon);
    public function getAll();
    public function getAllActive();
    public function getById($id);
    public function getByCode($code);
    public function decrementCouponQuantityByOne(array $coupon);
    public function update(array $data, $coupon);
    public function switchStatus($coupon);
}
