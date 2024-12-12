<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function getDashboardData(Request $request)
    {
        $branchName = $request->query('branch_name');
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);

        try {
            // Gọi DashboardService để xử lý
            $dashboardData = $this->dashboardService->getDashboardData($branchName, $month, $year);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $dashboardData,
        ]);
    }
}
