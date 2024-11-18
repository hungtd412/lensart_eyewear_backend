<?php

namespace App\Repositories;

interface OrderRepositoryInterface {
    public function store(array $data);
    public function getAll();
    public function getById(array $id);
    public function update(array $data, $order);
    public function changeOrderStatus($id, $newOrderStatus);
    public function changePaymentStatus($id, $newPaymentStatus);
    public function cancel($id);
    public function switchStatus($id);
}
