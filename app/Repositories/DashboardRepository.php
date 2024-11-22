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
        return Order::where('order_status', 'Đã giao')->count();
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

    // Calculate profit margin
    // public function getProfitMargin(): float
    // {
    //     // Gọi hàm getRevenue để lấy tổng doanh thu
    //     $revenue = $this->getRevenue();

    //     // Tính tổng chi phí sản phẩm từ OrderDetail và Products
    //     $cost = OrderDetail::whereHas('order', function ($query) {
    //         $query->where('order_status', '!=', 'Đã hủy'); // Loại bỏ các đơn hàng bị hủy
    //     })
    //         ->join('products', 'order_details.product_id', '=', 'products.id')
    //         ->sum(DB::raw('order_details.quantity * products.cost')); // Assuming 'cost' column exists in `products`

    //     // Tránh trường hợp chia cho 0
    //     if ($revenue == 0) {
    //         return 0;
    //     }

    //     // Tính tỷ lệ lợi nhuận (Profit Margin)
    //     return (($revenue - $cost) / $revenue) * 100;
    // }
}
