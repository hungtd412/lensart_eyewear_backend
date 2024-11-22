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

    // Fetch profit margin
    // public function getProfitMargin(): JsonResponse
    // {
    //     $profitMargin = $this->dashboardService->calculateProfitMargin();
    //     return response()->json(['profit_margin' => round($profitMargin, 2)], 200); // Round to 2 decimal places
    // }
}
