<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getBranchIdByName(string $branchName): ?int
    {
        $branch = DB::table('branches')->where('name', $branchName)->first();
        return $branch ? $branch->id : null;
    }

    public function getBranchNameById(int $branchId): ?string
    {
        $branch = Branch::find($branchId);
        return $branch ? $branch->name : null;
    }

    public function getRevenue($branchId, $month, $year)
    {
        return Order::where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('payment_status', 'Đã thanh toán')
            ->where('order_status', '!=', 'Đã hủy')
            ->sum('total_price');
    }

    public function getDeliveredOrders($branchId, $month, $year)
    {
        return Order::where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('order_status', 'Đã giao')
            ->count();
    }

    public function getPendingOrders($branchId, $month, $year)
    {
        return Order::where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('order_status', 'Đang xử lý')
            ->count();
    }

    public function getCancelledOrders($branchId, $month, $year)
    {
        return Order::where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('order_status', 'Đã hủy')
            ->count();
    }

    public function getProductsSold($branchId, $month, $year)
    {
        return OrderDetail::whereHas('order', function ($query) use ($branchId, $month, $year) {
            $query->where('branch_id', $branchId)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->where('order_status', '!=', 'Đã hủy');
        })->sum('quantity');
    }

    public function getNewCustomers($branchId, $month, $year)
    {
        $branchName = $this->getBranchNameById($branchId);
        return DB::table('users')
            ->where('role_id', 3) // Assuming role_id = 3 is for customers
            ->where('address', 'LIKE', "%$branchName%")
            ->whereYear('created_time', $year)
            ->whereMonth('created_time', $month)
            ->count();
    }

    public function getDailyRevenue($branchId, $month, $year)
    {
        // Lấy dữ liệu doanh thu từ cơ sở dữ liệu
        $dailyRevenue = Order::select(
            DB::raw('DAY(date) as day'),
            DB::raw('SUM(total_price) as revenue')
        )
            ->where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('payment_status', 'Đã thanh toán')
            ->groupBy(DB::raw('DAY(date)'))
            ->orderBy(DB::raw('DAY(date)'))
            ->get()
            ->keyBy('day'); // Chuyển thành key-value để dễ xử lý

        // Lấy số ngày trong tháng
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Tạo danh sách đầy đủ các ngày trong tháng
        $result = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $result[$day] = isset($dailyRevenue[$day]) ? (float) $dailyRevenue[$day]->revenue : 0.0;
        }

        return $result;
    }



    public function getOrderStatusOverview($branchId, $month, $year)
    {
        // Lấy dữ liệu trạng thái đơn hàng từ cơ sở dữ liệu
        $orderStatusOverview = Order::select(
            DB::raw('DAY(date) as day'),
            DB::raw('SUM(CASE WHEN order_status = "Đã giao" THEN 1 ELSE 0 END) as delivered_orders'),
            DB::raw('SUM(CASE WHEN order_status = "Đang xử lý" THEN 1 ELSE 0 END) as processed_orders'),
            DB::raw('SUM(CASE WHEN order_status = "Đã hủy" THEN 1 ELSE 0 END) as cancelled_orders')
        )
            ->where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->groupBy(DB::raw('DAY(date)'))
            ->orderBy(DB::raw('DAY(date)'))
            ->get()
            ->keyBy('day'); // Chuyển thành key-value để dễ xử lý

        // Lấy số ngày trong tháng
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Tạo danh sách đầy đủ các ngày trong tháng
        $result = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $result[$day] = [
                'delivered_orders' => isset($orderStatusOverview[$day]) ? (int) $orderStatusOverview[$day]->completed_orders : 0,
                'processed_orders' => isset($orderStatusOverview[$day]) ? (int) $orderStatusOverview[$day]->processed_orders : 0,
                'cancelled_orders' => isset($orderStatusOverview[$day]) ? (int) $orderStatusOverview[$day]->cancelled_orders : 0,
            ];
        }

        return $result;
    }
}
