<?php

namespace App\Repositories;

interface DashboardRepositoryInterface
{
    public function getTodaysOrders();
    public function getRevenue();
    public function getCompletedOrders();
    public function getPendingOrders();
    public function getCancelledOrders();

    public function getProductsSold(): int;

    public function getStock();

    public function getNewCustomers();
}
