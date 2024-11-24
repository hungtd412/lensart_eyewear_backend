<?php

namespace App\Repositories;

interface PayOSTransRepositoryInterface {
    public function store(array $payOSTrans);
    public function getAll();
    public function getAllUnpaid();
    public function getByOrderCode($orderCode);
    public function getByOrderId($orderId);
    public function updateAmount($payOSTrans, $amount);
    public function delete($orderCode);
}
