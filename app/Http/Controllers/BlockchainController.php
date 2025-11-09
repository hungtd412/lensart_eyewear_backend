<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

class BlockchainController extends Controller
{
    /**
     * Lấy thông tin contracts cho frontend
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getContractInfo(Request $request): JsonResponse
    {
        try {
            $network = $request->query('network', 'sepolia'); // Default to sepolia
            
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            
            if (!File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract info not found for network: ' . $network,
                    'error' => 'Please run: cd contracts && npm run export:contracts'
                ], 404);
            }

            $contractInfo = json_decode(File::get($contractsPath), true);
            
            return response()->json([
                'success' => true,
                'data' => $contractInfo
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting contract info: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading contract information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin contract cụ thể
     * 
     * @param Request $request
     * @param string $contractName
     * @return JsonResponse
     */
    public function getContract(Request $request, string $contractName): JsonResponse
    {
        try {
            $network = $request->query('network', 'sepolia');
            
            $contractPath = base_path('contracts/exports/' . $contractName . '-' . $network . '.json');
            
            if (!File::exists($contractPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract not found: ' . $contractName,
                    'error' => 'Available contracts: LENSToken, LensArtPayment, LensArtOrderNFT'
                ], 404);
            }

            $contractData = json_decode(File::get($contractPath), true);
            
            return response()->json([
                'success' => true,
                'data' => $contractData
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting contract: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading contract',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách các networks available
     * 
     * @return JsonResponse
     */
    public function getAvailableNetworks(): JsonResponse
    {
        try {
            $exportsPath = base_path('contracts/exports');
            
            if (!File::exists($exportsPath)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No exports found. Please export contracts first.'
                ]);
            }

            $files = File::files($exportsPath);
            $networks = [];
            
            foreach ($files as $file) {
                if (str_contains($file->getFilename(), 'frontend-config-')) {
                    $network = str_replace(['frontend-config-', '.json'], '', $file->getFilename());
                    $networks[] = $network;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $networks
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting networks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading networks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify wallet address format
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyAddress(Request $request): JsonResponse
    {
        try {
            $address = $request->input('address');
            
            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address is required'
                ], 400);
            }

            // Basic Ethereum address validation
            $isValid = preg_match('/^0x[a-fA-F0-9]{40}$/', $address);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'address' => $address,
                    'isValid' => (bool) $isValid
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error verifying address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request LENS tokens (faucet) cho user
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function requestLensTokens(Request $request): JsonResponse
    {
        try {
            $address = $request->input('address');
            $amount = $request->input('amount', '1000'); // Default: 1000 LENS
            $network = $request->input('network', 'sepolia'); // Default: sepolia
            
            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Địa chỉ ví không được để trống'
                ], 400);
            }

            // Validate Ethereum address format
            if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $address)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Địa chỉ ví không hợp lệ'
                ], 400);
            }

            // Validate amount (must be numeric and positive)
            if (!is_numeric($amount) || floatval($amount) <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng token không hợp lệ'
                ], 400);
            }

            // Check if contracts directory exists
            $contractsPath = base_path('contracts');
            if (!File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contracts directory not found'
                ], 500);
            }

            // Check if deployment file exists
            $deploymentFile = base_path("contracts/deployments/{$network}.json");
            if (!File::exists($deploymentFile)) {
                return response()->json([
                    'success' => false,
                    'message' => "Deployment file not found for network: {$network}",
                    'error' => 'Please deploy contracts first'
                ], 404);
            }

            Log::info("Requesting LENS tokens", [
                'address' => $address,
                'amount' => $amount,
                'network' => $network
            ]);

            // Chạy script faucet-lens.js
            $contractsDir = base_path('contracts');
            
            // Escape address and amount for command line
            $escapedAddress = escapeshellarg($address);
            $escapedAmount = escapeshellarg($amount);
            
            // Build command - Process facade sẽ tự động handle working directory
            $command = "npm run faucet:lens -- --address {$escapedAddress} --amount {$escapedAmount}";
            
            // Run command in contracts directory
            $process = Process::timeout(120)
                ->path($contractsDir)
                ->run($command);
            
            if (!$process->successful()) {
                $errorOutput = $process->errorOutput();
                Log::error('Faucet script failed', [
                    'address' => $address,
                    'error' => $errorOutput,
                    'exit_code' => $process->exitCode()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Không thể transfer LENS tokens',
                    'error' => $errorOutput ?: 'Unknown error',
                    'details' => 'Có thể do: không đủ ETH trong deployer wallet, không đủ LENS tokens trong deployer, hoặc lỗi kết nối blockchain'
                ], 500);
            }

            $output = $process->output();
            
            // Parse output to extract transaction hash if available
            $txHash = null;
            if (preg_match('/Transaction hash:\s*(0x[a-fA-F0-9]{64})/', $output, $matches)) {
                $txHash = $matches[1];
            }

            Log::info('LENS tokens transferred successfully', [
                'address' => $address,
                'amount' => $amount,
                'tx_hash' => $txHash
            ]);

            return response()->json([
                'success' => true,
                'message' => "Đã transfer {$amount} LENS tokens thành công",
                'data' => [
                    'address' => $address,
                    'amount' => $amount,
                    'network' => $network,
                    'transaction_hash' => $txHash,
                    'explorer_url' => $txHash 
                        ? "https://dashboard.tenderly.co/trinhhhh453543/crypto/tx/{$network}/{$txHash}" 
                        : null
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error requesting LENS tokens: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi request LENS tokens',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kiểm tra số dư LENS token của một địa chỉ ví
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function checkLensBalance(Request $request): JsonResponse
    {
        try {
            $address = $request->query('address');
            $network = $request->query('network', 'sepolia');
            
            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Địa chỉ ví không được để trống'
                ], 400);
            }

            // Validate Ethereum address format
            if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $address)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Địa chỉ ví không hợp lệ'
                ], 400);
            }

            // Check deployment file
            $deploymentFile = base_path("contracts/deployments/{$network}.json");
            if (!File::exists($deploymentFile)) {
                return response()->json([
                    'success' => false,
                    'message' => "Deployment file not found for network: {$network}"
                ], 404);
            }

            $deploymentInfo = json_decode(File::get($deploymentFile), true);
            $lensTokenAddress = $deploymentInfo['contracts']['LENSToken'] ?? null;

            if (!$lensTokenAddress) {
                return response()->json([
                    'success' => false,
                    'message' => 'LENSToken contract address not found'
                ], 404);
            }

            // Lấy RPC URL từ contract info
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            
            if (!File::exists($contractsPath)) {
                Log::error('Contract config file not found', ['path' => $contractsPath]);
                return response()->json([
                    'success' => false,
                    'message' => 'Contract config file not found for network: ' . $network
                ], 500);
            }
            
            $contractInfo = json_decode(File::get($contractsPath), true);
            $rpcUrl = $contractInfo['rpcUrl'] ?? null;
            
            if (!$rpcUrl) {
                Log::error('RPC URL not found in contract info', ['contract_info' => $contractInfo]);
                return response()->json([
                    'success' => false,
                    'message' => 'RPC URL not found for network: ' . $network
                ], 500);
            }
            
            Log::info('Checking LENS balance via RPC', [
                'address' => $address,
                'contract_address' => $lensTokenAddress,
                'rpc_url' => $rpcUrl,
                'network' => $network
            ]);
            
            // Gọi RPC trực tiếp để check balance (không cần Node.js)
            try {
                $balance = $this->getTokenBalanceViaRPC($rpcUrl, $lensTokenAddress, $address);
                
                if ($balance === null) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể lấy số dư từ blockchain',
                        'error' => 'RPC call failed'
                    ], 500);
                }
                
                // Format balance từ wei sang ether (18 decimals)
                $balanceFormatted = $this->weiToEther($balance);
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'address' => $address,
                        'balance' => $balanceFormatted,
                        'balance_raw' => $balance,
                        'network' => $network,
                        'lens_token_address' => $lensTokenAddress
                    ]
                ]);
                
            } catch (\Exception $e) {
                Log::error('RPC call failed', [
                    'error' => $e->getMessage(),
                    'rpc_url' => $rpcUrl,
                    'contract_address' => $lensTokenAddress,
                    'user_address' => $address
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể kiểm tra số dư',
                    'error' => $e->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error checking LENS balance: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi kiểm tra số dư',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gọi RPC để lấy token balance
     * 
     * @param string $rpcUrl
     * @param string $contractAddress
     * @param string $userAddress
     * @return string|null Balance in wei (hex string) or null on error
     */
    private function getTokenBalanceViaRPC(string $rpcUrl, string $contractAddress, string $userAddress): ?string
    {
        try {
            // Function signature: balanceOf(address)
            // Function selector: 0x70a08231 (first 4 bytes of keccak256("balanceOf(address)"))
            $functionSelector = '0x70a08231';
            
            // Encode address parameter (remove 0x prefix, pad to 32 bytes)
            $addressParam = strtolower($userAddress);
            if (strpos($addressParam, '0x') === 0) {
                $addressParam = substr($addressParam, 2);
            }
            $addressParam = str_pad($addressParam, 64, '0', STR_PAD_LEFT);
            
            // Data: function selector + encoded address
            $data = $functionSelector . $addressParam;
            
            // Prepare RPC request
            $requestData = [
                'jsonrpc' => '2.0',
                'method' => 'eth_call',
                'params' => [
                    [
                        'to' => $contractAddress,
                        'data' => $data
                    ],
                    'latest'
                ],
                'id' => 1
            ];
            
            // Make HTTP request to RPC endpoint using Laravel HTTP client
            try {
                $response = Http::timeout(30)
                    ->withoutVerifying() // Tắt SSL verification cho Tenderly RPC
                    ->post($rpcUrl, $requestData);
                
                if (!$response->successful()) {
                    Log::error('RPC returned non-200 status', [
                        'http_code' => $response->status(), 
                        'response' => $response->body()
                    ]);
                    return null;
                }
                
                $result = $response->json();
                
                if ($result === null) {
                    Log::error('Failed to parse RPC JSON response', ['response' => $response->body()]);
                    return null;
                }
                
            } catch (\Exception $e) {
                Log::error('Exception calling RPC', [
                    'error' => $e->getMessage(),
                    'rpc_url' => $rpcUrl,
                    'trace' => $e->getTraceAsString()
                ]);
                return null;
            }
            
            // Handle error in result
            if (isset($result['error'])) {
                Log::error('RPC error', ['error' => $result['error']]);
                return null;
            }
            
            // Get result - RPC response structure: {"jsonrpc":"2.0","result":"0x...","id":1}
            if (!isset($result['result'])) {
                Log::error('Invalid RPC response - no result field', ['result' => $result]);
                return null;
            }
            
            $balanceHex = $result['result'];
            
            // Handle empty or invalid response
            if ($balanceHex === '0x' || empty($balanceHex)) {
                return '0x0';
            }
            
            // Validate hex format
            if (!preg_match('/^0x[a-fA-F0-9]+$/', $balanceHex)) {
                Log::error('Invalid hex format in RPC response', ['result' => $balanceHex]);
                return null;
            }
            
            return $balanceHex;
            
        } catch (\Exception $e) {
            Log::error('Exception in getTokenBalanceViaRPC', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Convert wei (hex string) to ether (decimal string)
     * 
     * @param string $weiHex Balance in wei as hex string (e.g., "0x1bc16d674ec80000")
     * @return string Balance in ether as decimal string (e.g., "2.0")
     */
    private function weiToEther(string $weiHex): string
    {
        // Remove 0x prefix
        if (strpos($weiHex, '0x') === 0) {
            $weiHex = substr($weiHex, 2);
        }
        
        // Convert hex to decimal using bcmath
        if (!function_exists('bcadd')) {
            // Fallback: use basic conversion (may lose precision for very large numbers)
            $weiDecimal = (string)hexdec($weiHex);
        } else {
            // Use bcmath for precise conversion
            $weiDecimal = '0';
            $length = strlen($weiHex);
            for ($i = 0; $i < $length; $i++) {
                $digit = hexdec($weiHex[$i]);
                $weiDecimal = bcmul($weiDecimal, '16', 0);
                $weiDecimal = bcadd($weiDecimal, (string)$digit, 0);
            }
        }
        
        // Divide by 10^18 to convert wei to ether
        $divisor = '1000000000000000000'; // 10^18
        
        if (function_exists('bcdiv')) {
            $ether = bcdiv($weiDecimal, $divisor, 18);
            // Remove trailing zeros
            $ether = rtrim(rtrim($ether, '0'), '.');
        } else {
            // Fallback: basic division (may lose precision)
            $weiInt = (float)$weiDecimal;
            $ether = (string)($weiInt / 1000000000000000000);
            // Format to remove unnecessary decimals
            $ether = rtrim(rtrim(sprintf('%.18f', $ether), '0'), '.');
        }
        
        return $ether ?: '0';
    }
}

