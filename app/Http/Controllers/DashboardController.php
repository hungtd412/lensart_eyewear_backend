<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function getTodaysOrders(): JsonResponse
    {
        $result = $this->dashboardService->getTodaysOrders();
        return response()->json(['data' => $result], 200);
    }

    public function getRevenue(): JsonResponse
    {
        $result = $this->dashboardService->getRevenue();
        return response()->json(['data' => $result], 200);
    }

    public function getCompletedOrders(): JsonResponse
    {
        $result = $this->dashboardService->getCompletedOrders();
        return response()->json(['data' => $result], 200);
    }

    public function getPendingOrders(): JsonResponse
    {
        $result = $this->dashboardService->getPendingOrders();
        return response()->json(['data' => $result], 200);
    }

    public function getCancelledOrders(): JsonResponse
    {
        $result = $this->dashboardService->getCancelledOrders();
        return response()->json(['data' => $result], 200);
    }

    // Fetch total products sold
    public function getProductsSold(): JsonResponse
    {
        $productsSold = $this->dashboardService->calculateProductsSold();
        return response()->json(['products_sold' => $productsSold], 200);
    }

    public function getStock()
    {
        $stock = $this->dashboardService->getStock();
        return response()->json(['stock' => $stock], 200);
    }

    public function getNewCustomers()
    {
        $newCustomers = $this->dashboardService->getNewCustomers();
        return response()->json(['new_customers' => $newCustomers], 200);
    }

    public function getAverageOrderValue(): JsonResponse
    {
        $averageOrderValue = $this->dashboardService->getAverageOrderValue();

        return response()->json([
            'average_order_value' => round($averageOrderValue, 2), // Làm tròn 2 chữ số thập phân
        ]);
    }
}
