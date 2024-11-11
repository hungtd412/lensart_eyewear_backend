<?php

namespace App\Repositories;

interface CouponRepositoryInterface {
    public function store(array $coupon);
    public function getAll();
    public function getById($id);
    public function getByCode(array $coupon);
    public function update(array $data, $coupon);
    public function switchStatus($coupon);
}
