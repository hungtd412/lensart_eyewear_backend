<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStatusParameter {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $status = $request->route()->parameter('status');
        if (!$status) {
            return response()->json([
                'message' => 'Missing type parameter'
            ], 400);
        }

        $orderStatusList = ['Đang xử lý', 'Đã xử lý và sẵn sàng giao hàng', 'Đang giao hàng', 'Đã giao', 'Đã hủy'];
        $paymentStatusList = ['Chưa thanh toán', 'Đã thanh toán'];

        if (!in_array($status, $orderStatusList) && !in_array($status, $paymentStatusList)) {
            return response()->json([
                'message' => 'Invalid status'
            ], 400);
        }

        return $next($request);
    }
}
