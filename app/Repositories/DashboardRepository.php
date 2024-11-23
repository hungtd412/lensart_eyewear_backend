<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    private function getBranchIdByName($branchName)
    {
        $branch = Branch::where('name', $branchName)->first();

        if (!$branch) {
            throw new \Exception("Branch '$branchName' not found");
        }

        return $branch->id;
    }

    public function getRevenue($branchName, $month, $year)
    {
        $branchId = $this->getBranchIdByName($branchName);

        return Order::where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('payment_status', 'Đã thanh toán')
            ->where('order_status', '!=', 'Đã hủy')
            ->sum('total_price');
    }

    public function getCompletedOrders($branchName, $month, $year)
    {
        $branchId = $this->getBranchIdByName($branchName);

        return Order::where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('order_status', 'Đã giao')
            ->where('payment_status', 'Đã thanh toán')
            ->count();
    }

    public function getPendingOrders($branchName, $month, $year)
    {
        $branchId = $this->getBranchIdByName($branchName);

        return Order::where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('order_status', 'Đang xử lý')
            ->count();
    }

    public function getCancelledOrders($branchName, $month, $year)
    {
        $branchId = $this->getBranchIdByName($branchName);

        return Order::where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('order_status', 'Đã hủy')
            ->count();
    }

    public function getProductsSold($branchName, $month, $year)
    {
        $branchId = $this->getBranchIdByName($branchName);

        return OrderDetail::whereHas('order', function ($query) use ($branchId, $month, $year) {
            $query->where('branch_id', $branchId)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->where('order_status', '!=', 'Đã hủy');
        })->sum('quantity');
    }

    public function getNewCustomers($branchName, $month, $year)
    {
        return DB::table('users')
            ->where('role_id', 3) // Assuming role_id = 3 is for customers
            ->where('address', 'LIKE', "%$branchName%")
            ->whereYear('created_time', $year)
            ->whereMonth('created_time', $month)
            ->count();
    }

    public function getDailyRevenue($branchName, $month, $year)
    {
        $branchId = $this->getBranchIdByName($branchName);

        return Order::select(
            DB::raw('DAY(date) as day'),
            DB::raw('SUM(total_price) as revenue')
        )
            ->where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('payment_status', 'Đã thanh toán')
            ->groupBy(DB::raw('DAY(date)'))
            ->orderBy(DB::raw('DAY(date)'))
            ->get();
    }

    public function getOrderStatusOverview($branchName, $month, $year)
    {
        $branchId = $this->getBranchIdByName($branchName);

        return Order::select(
            DB::raw('DAY(date) as day'),
            DB::raw('SUM(CASE WHEN order_status = "Đã giao" THEN 1 ELSE 0 END) as completed_orders'),
            DB::raw('SUM(CASE WHEN order_status = "Đang xử lý" THEN 1 ELSE 0 END) as processed_orders'),
            DB::raw('SUM(CASE WHEN order_status = "Đã hủy" THEN 1 ELSE 0 END) as cancelled_orders')
        )
            ->where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->groupBy(DB::raw('DAY(date)'))
            ->orderBy(DB::raw('DAY(date)'))
            ->get();
    }
}
