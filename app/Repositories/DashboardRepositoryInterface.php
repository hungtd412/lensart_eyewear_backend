<?php

namespace App\Repositories;

interface DashboardRepositoryInterface
{
    public function getRevenue($branchId, $month, $year);
    public function getDeliveredOrders($branchId, $month, $year);
    public function getPendingOrders($branchId, $month, $year);
    public function getCancelledOrders($branchId, $month, $year);
    public function getProductsSold($branchId, $month, $year);
    public function getNewCustomers($branchId, $month, $year);
    public function getDailyRevenue($branchId, $month, $year);

    public function getOrderStatusOverview($branchId, $month, $year);

    public function getBranchIdByName(string $branchName): ?int;
}
