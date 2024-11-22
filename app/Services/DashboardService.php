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

    public function getTodaysOrders()
    {
        return $this->dashboardRepository->getTodaysOrders();
    }

    public function getRevenue()
    {
        return $this->dashboardRepository->getRevenue();
    }

    public function getCompletedOrders()
    {
        return $this->dashboardRepository->getCompletedOrders();
    }

    public function getPendingOrders()
    {
        return $this->dashboardRepository->getPendingOrders();
    }

    public function getCancelledOrders()
    {
        return $this->dashboardRepository->getCancelledOrders();
    }

    // Get products sold
    public function calculateProductsSold(): int
    {
        return $this->dashboardRepository->getProductsSold();
    }

    public function getStock()
    {
        return $this->dashboardRepository->getStock();
    }

    public function getNewCustomers()
    {
        return $this->dashboardRepository->getNewCustomers();
    }
}
