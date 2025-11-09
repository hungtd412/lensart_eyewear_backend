<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\OrderService;

class CryptoPaymentController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        parent::__construct();
    }

    /**
     * Lấy payment info (public - không cần auth)
     * 
     * @param Request $request
     * @param int $orderId
     * @return JsonResponse
     */
    public function getPaymentInfo(Request $request, $orderId): JsonResponse
    {
        try {
            // Validate order ID
            if (!is_numeric($orderId) || $orderId <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order ID không hợp lệ'
                ], 400);
            }

            // Kiểm tra order có tồn tại không (bypass auth check vì đây là public endpoint)
            // Sử dụng repository trực tiếp để tránh Gate check
            $orderRepository = app(\App\Repositories\OrderRepository::class);
            $order = $orderRepository->getById($orderId);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order không tồn tại'
                ], 404);
            }
            
            // Convert to object nếu là array
            if (is_array($order)) {
                $order = (object) $order;
            }

            // Kiểm tra payment method phải là Crypto
            if ($order->payment_method !== 'Crypto') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method không phải là Crypto'
                ], 400);
            }

            // Lấy contract info từ backend
            $network = $request->input('network', 'sepolia');
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            
            if (!File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract info not found for network: ' . $network
                ], 404);
            }

            $contractInfo = json_decode(File::get($contractsPath), true);

            // Tính toán số tiền cần thanh toán (tính bằng LENS token)
            $exchangeRate = 0.001; // TODO: Lấy từ config hoặc API
            $totalPriceVND = floatval($order->total_price);
            $totalPriceLENS = $totalPriceVND * $exchangeRate;

            // Trả về thông tin payment (không cần wallet address)
            return response()->json([
                'success' => true,
                'message' => 'Payment info retrieved successfully',
                'data' => [
                    'order_id' => $orderId,
                    'total_price_vnd' => $totalPriceVND,
                    'total_price_lens' => number_format($totalPriceLENS, 2, '.', ''),
                    'network' => $network,
                    'chain_id' => $contractInfo['chainId'] ?? 11155111,
                    'contracts' => [
                        'LENSToken' => $contractInfo['contracts']['LENSToken'],
                        'LensArtPayment' => $contractInfo['contracts']['LensArtPayment'],
                    ],
                    'abis' => [
                        'LENSToken' => $contractInfo['abis']['LENSToken'],
                        'LensArtPayment' => $contractInfo['abis']['LensArtPayment'],
                    ],
                    'rpc_url' => $contractInfo['rpcUrl'] ?? null,
                    'explorer_url' => $contractInfo['explorerUrl'] ?? 'https://dashboard.tenderly.co/trinhhhh453543/crypto',
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting payment info: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy payment info',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo payment request cho crypto payment
     * 
     * @param Request $request
     * @param int $orderId
     * @return JsonResponse
     */
    public function createPaymentRequest(Request $request, $orderId): JsonResponse
    {
        try {
            // Validate order ID
            if (!is_numeric($orderId) || $orderId <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order ID không hợp lệ'
                ], 400);
            }

            // Kiểm tra order có tồn tại không
            $orderResponse = $this->orderService->getById($orderId);
            
            if ($orderResponse->statusCode() !== 200) {
                $orderData = json_decode($orderResponse->getContent(), true);
                return response()->json([
                    'success' => false,
                    'message' => $orderData['message'] ?? 'Order không tồn tại'
                ], $orderResponse->statusCode());
            }
            
            $orderData = json_decode($orderResponse->getContent(), true);
            $order = (object) $orderData['data'];

            // Kiểm tra order đã được thanh toán chưa
            // Note: isPaid returns true if payment_status != 'Chưa thanh toán'
            if ($order->payment_status !== 'Chưa thanh toán') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng đã được thanh toán'
                ], 400);
            }

            // Kiểm tra payment method phải là Crypto
            if ($order->payment_method !== 'Crypto') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method không phải là Crypto'
                ], 400);
            }

            // Kiểm tra user có quyền truy cập order này không
            if (auth()->id() != $order->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập order này'
                ], 403);
            }

            // Lấy contract info từ backend
            $network = $request->input('network', 'sepolia');
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            
            if (!File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract info not found for network: ' . $network
                ], 404);
            }

            $contractInfo = json_decode(File::get($contractsPath), true);

            // Tính toán số tiền cần thanh toán (tính bằng LENS token)
            // Giả sử: 1 VND = 0.001 LENS (có thể thay đổi theo tỷ giá thực tế)
            $exchangeRate = 0.001; // TODO: Lấy từ config hoặc API
            $totalPriceVND = floatval($order->total_price);
            $totalPriceLENS = $totalPriceVND * $exchangeRate;

            // Lấy thông tin cần thiết
            $walletAddress = $request->input('wallet_address');
            
            if (!$walletAddress) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wallet address không được để trống'
                ], 400);
            }

            // Validate wallet address format
            if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $walletAddress)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wallet address không hợp lệ'
                ], 400);
            }

            Log::info('Creating crypto payment request', [
                'order_id' => $orderId,
                'wallet_address' => $walletAddress,
                'total_price_lens' => $totalPriceLENS,
                'network' => $network
            ]);

            // Trả về thông tin payment request
            return response()->json([
                'success' => true,
                'message' => 'Payment request created successfully',
                'data' => [
                    'order_id' => $orderId,
                    'wallet_address' => $walletAddress,
                    'total_price_vnd' => $totalPriceVND,
                    'total_price_lens' => number_format($totalPriceLENS, 2, '.', ''),
                    'network' => $network,
                    'chain_id' => $contractInfo['chainId'] ?? 11155111,
                    'contracts' => [
                        'LENSToken' => $contractInfo['contracts']['LENSToken'],
                        'LensArtPayment' => $contractInfo['contracts']['LensArtPayment'],
                    ],
                    'abis' => [
                        'LENSToken' => $contractInfo['abis']['LENSToken'],
                        'LensArtPayment' => $contractInfo['abis']['LensArtPayment'],
                    ],
                    'rpc_url' => $contractInfo['rpcUrl'] ?? null,
                    'explorer_url' => $contractInfo['explorerUrl'] ?? 'https://dashboard.tenderly.co/trinhhhh453543/crypto',
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating crypto payment request: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo payment request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xác nhận payment đã hoàn thành (gọi từ frontend sau khi user đã thực hiện transaction)
     * 
     * @param Request $request
     * @param int $orderId
     * @return JsonResponse
     */
    public function confirmPayment(Request $request, $orderId): JsonResponse
    {
        try {
            // TODO: Verify transaction trên blockchain
            // 1. Kiểm tra transaction hash
            // 2. Verify transaction đã được confirm
            // 3. Verify số tiền đã được transfer đúng
            // 4. Update payment status của order

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error confirming payment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xác nhận payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

