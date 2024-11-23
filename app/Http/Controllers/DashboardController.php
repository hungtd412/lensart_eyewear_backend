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
        $branchName = $request->input('branch_name');
        $month = $request->input('month');
        $year = $request->input('year');

        $data = $this->dashboardService->getDashboardData($branchName, $month, $year);

        return response()->json($data);
    }
}
