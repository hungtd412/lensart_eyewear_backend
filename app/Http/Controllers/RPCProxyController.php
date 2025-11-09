<?php

namespace App\Http\Controllers;

use App\Services\Web3Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class RPCProxyController extends Controller
{
    /**
     * Proxy RPC requests to avoid CORS issues
     * Frontend can call this endpoint instead of connecting directly to RPC
     */
    public function proxy(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            
            // Detect request format - ethers.js sends standard JSON-RPC format
            $isJsonRpcFormat = isset($requestData['jsonrpc']) && isset($requestData['method']);
            
            if ($isJsonRpcFormat) {
                // Standard JSON-RPC format from ethers.js
                $method = $requestData['method'];
                $params = $requestData['params'] ?? [];
                $id = $requestData['id'] ?? 1;
                
                // Get network from query parameter (default to sepolia)
                $network = $request->query('network', 'sepolia');
            } else {
                // Custom format
                $request->validate([
                    'method' => 'required|string',
                    'params' => 'nullable|array',
                    'network' => 'nullable|string|in:sepolia,mainnet'
                ]);
                
                $network = $request->input('network', 'sepolia');
                $method = $request->input('method');
                $params = $request->input('params', []);
                $id = $request->input('id', 1);
            }

            // Load network config
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            
            if (!\Illuminate\Support\Facades\File::exists($contractsPath)) {
                return response()->json([
                    'jsonrpc' => '2.0',
                    'error' => [
                        'code' => -32603,
                        'message' => 'Contract config not found for network: ' . $network
                    ],
                    'id' => $id
                ], 404);
            }

            $config = json_decode(\Illuminate\Support\Facades\File::get($contractsPath), true);
            $rpcUrl = $config['rpcUrl'] ?? null;

            if (!$rpcUrl) {
                return response()->json([
                    'jsonrpc' => '2.0',
                    'error' => [
                        'code' => -32603,
                        'message' => 'RPC URL not found for network: ' . $network
                    ],
                    'id' => $id
                ], 404);
            }

            // Prepare RPC request
            $rpcRequest = [
                'jsonrpc' => '2.0',
                'method' => $method,
                'params' => $params,
                'id' => $id
            ];

            // Forward request to RPC
            $response = Http::timeout(30)
                ->withoutVerifying()
                ->post($rpcUrl, $rpcRequest);

            if (!$response->successful()) {
                Log::error('RPC proxy failed', [
                    'method' => $method,
                    'network' => $network,
                    'http_code' => $response->status(),
                    'response' => $response->body()
                ]);

                return response()->json([
                    'jsonrpc' => '2.0',
                    'error' => [
                        'code' => -32603,
                        'message' => 'RPC request failed: ' . $response->status()
                    ],
                    'id' => $id
                ], $response->status());
            }

            $result = $response->json();

            // Return RPC response as-is
            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('RPC proxy error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'jsonrpc' => '2.0',
                'error' => [
                    'code' => -32603,
                    'message' => 'Internal error: ' . $e->getMessage()
                ],
                'id' => $requestData['id'] ?? ($request->input('id', 1))
            ], 500);
        }
    }

    /**
     * Get RPC URL for frontend (so frontend knows which endpoint to use)
     */
    public function getRpcUrl(Request $request): JsonResponse
    {
        try {
            $network = $request->input('network', 'sepolia');
            
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            
            if (!\Illuminate\Support\Facades\File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract config not found for network: ' . $network
                ], 404);
            }

            $config = json_decode(\Illuminate\Support\Facades\File::get($contractsPath), true);
            $rpcUrl = $config['rpcUrl'] ?? null;

            if (!$rpcUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'RPC URL not found for network: ' . $network
                ], 404);
            }

            // Return backend proxy URL instead of direct RPC URL
            $proxyUrl = url('/api/rpc-proxy/proxy');

            return response()->json([
                'success' => true,
                'data' => [
                    'network' => $network,
                    'rpc_url' => $rpcUrl, // Original RPC URL (for reference)
                    'proxy_url' => $proxyUrl, // Backend proxy URL (use this in frontend)
                    'chain_id' => $config['chainId'] ?? 11155111,
                    'note' => 'Use proxy_url in frontend to avoid CORS issues'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting RPC URL', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get RPC URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

