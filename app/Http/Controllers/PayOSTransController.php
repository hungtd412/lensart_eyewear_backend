<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CheckOutService;
use App\Services\PayOSTransService;

class PayOSTransController extends Controller {
    protected $payOSTransService;
    protected $checkOutService;

    public function __construct(PayOSTransService $payOSTransService, CheckOutService $checkOutService) {
        $this->payOSTransService = $payOSTransService;
        $this->checkOutService = $checkOutService;
        parent::__construct();
    }

    public function store($data) {
        return $this->payOSTransService->store($data);
    }

    public function index() {
        return $this->payOSTransService->getAll();
    }

    public function getByOrderCode($orderCode) {
        return $this->payOSTransService->getByOrderCode($orderCode);
    }

    public function getByOrderId($orderId) {
        return $this->payOSTransService->getByOrderId($orderId);
    }

    public function update($orderId) {
        return $this->payOSTransService->update($this->checkOutService, $this->payOS, $orderId);
    }

    public function refresh() {
        return $this->payOSTransService->refresh($this->checkOutService, $this->payOS);
    }
}
