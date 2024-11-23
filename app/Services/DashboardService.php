<?php

namespace App\Services;

use App\Repositories\DashboardRepositoryInterface;

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDashboardData($branchName, $month, $year)
    {
        return [
            'revenue' => $this->dashboardRepository->getRevenue($branchName, $month, $year),
            'completed_orders' => $this->dashboardRepository->getCompletedOrders($branchName, $month, $year),
            'pending_orders' => $this->dashboardRepository->getPendingOrders($branchName, $month, $year),
            'cancelled_orders' => $this->dashboardRepository->getCancelledOrders($branchName, $month, $year),
            'products_sold' => $this->dashboardRepository->getProductsSold($branchName, $month, $year),
            'new_customers' => $this->dashboardRepository->getNewCustomers($branchName, $month, $year),
            'daily_revenue' => $this->dashboardRepository->getDailyRevenue($branchName, $month, $year),
            'order_status_overview' => $this->dashboardRepository->getOrderStatusOverview($branchName, $month, $year),
        ];
    }
}
