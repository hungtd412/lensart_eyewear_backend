<?php

namespace App\Services;

use App\Services\OrderService;

use App\Repositories\DashboardRepositoryInterface;

class DashboardService
{
    protected $dashboardRepository;
    protected $orderService;

    public function __construct(DashboardRepositoryInterface $dashboardRepository, OrderService $orderService)
    {
        $this->dashboardRepository = $dashboardRepository;
        $this->orderService = $orderService;
    }

    public function getDashboardData(string $branchName, $month, $year)
    {
        $branchId = $this->dashboardRepository->getBranchIdByName($branchName);
        // Kiểm tra quyền xem chi nhánh thông qua OrderService
        if (!$this->orderService->isValidUser($branchId)) {
            throw new \Exception("Bạn không có quyền xem dữ liệu của chi nhánh này.");
        }

        // Lấy thông tin theo chi nhánh sau khi xác minh quyền
        return [
            'revenue' => $this->dashboardRepository->getRevenue($branchId, $month, $year),
            'completed_orders' => $this->dashboardRepository->getCompletedOrders($branchId, $month, $year),
            'pending_orders' => $this->dashboardRepository->getPendingOrders($branchId, $month, $year),
            'cancelled_orders' => $this->dashboardRepository->getCancelledOrders($branchId, $month, $year),
            'products_sold' => $this->dashboardRepository->getProductsSold($branchId, $month, $year),
            'new_customers' => $this->dashboardRepository->getNewCustomers($branchId, $month, $year),
            'daily_revenue' => $this->dashboardRepository->getDailyRevenue($branchId, $month, $year),
            'order_status_overview' => $this->dashboardRepository->getOrderStatusOverview($branchId, $month, $year),
        ];
    }
}
