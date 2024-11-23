<?php

namespace App\Repositories;

interface DashboardRepositoryInterface
{
    public function getRevenue($branchName, $month, $year);
    public function getCompletedOrders($branchName, $month, $year);
    public function getPendingOrders($branchName, $month, $year);
    public function getCancelledOrders($branchName, $month, $year);
    public function getProductsSold($branchName, $month, $year);
    public function getNewCustomers($branchName, $month, $year);
    public function getDailyRevenue($branchName, $month, $year);
    public function getOrderStatusOverview($branchName, $month, $year);
}
