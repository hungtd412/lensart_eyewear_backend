<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTodaysOrders()
    {
        return Order::whereDate('date', now()->toDateString())->count();
    }

    public function getRevenue()
    {
        return Order::whereDate('date', now()->toDateString())
            ->where('payment_status', 'Đã thanh toán')
            ->where('order_status', '!=', 'Đã hủy')
            ->sum('total_price');
    }

    public function getCompletedOrders()
    {
        return Order::where('order_status', 'Đã giao')
            ->where('payment_status', 'Đã thanh toán')
            ->count();
    }


    public function getPendingOrders()
    {
        return Order::where('order_status', 'Đang xử lý')->count();
    }

    public function getCancelledOrders()
    {
        return Order::where('order_status', 'Đã hủy')->count();
    }

    // Fetch total products sold
    public function getProductsSold(): int
    {
        return OrderDetail::whereHas('order', function ($query) {
            $query->where('order_status', '!=', 'Đã hủy'); // Exclude canceled orders
        })->sum('quantity');
    }

    public function getStock()
    {
        return DB::table('product_details')
            ->join('products', 'product_details.product_id', '=', 'products.id') // Join bảng products
            ->where('product_details.status', '=', 'active') // Kiểm tra status của product_details
            ->where('products.status', '=', 'active') // Kiểm tra status của products
            ->sum('product_details.quantity'); // Tính tổng quantity
    }


    public function getNewCustomers()
    {
        return DB::table('users')
            ->where('role_id', '=', 3) // Assuming role_id = 3 is for customers
            ->whereDate('email_verified_at', '>=', now()->subMonth()->toDateString()) // New customers in the last month
            ->count();
    }

    public function getAverageOrderValue(): float
    {
        // Tổng doanh thu
        $totalRevenue = Order::where('order_status', '!=', 'Đã hủy') // Bỏ qua đơn hàng đã hủy
            ->sum('total_price');

        // Tổng số đơn hàng
        $totalOrders = Order::where('order_status', '!=', 'Đã hủy') // Bỏ qua đơn hàng đã hủy
            ->count();

        // Tránh chia cho 0
        if ($totalOrders === 0) {
            return 0;
        }

        // Tính Average Order Value (AOV)
        return $totalRevenue / $totalOrders;
    }
}
