<?php

namespace App\Http\Controllers;

use App\Services\Web3Service;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class TokenController extends Controller
{
    /**
     * Get token balance
     */
    public function getBalance(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $web3Service = new Web3Service($network);

            // Get contract address
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            if (!File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract config not found'
                ], 404);
            }

            $config = json_decode(File::get($contractsPath), true);
            $tokenContract = $config['contracts']['LENSToken'] ?? null;

            if (!$tokenContract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token contract address not found'
                ], 404);
            }

            $balance = $web3Service->getTokenBalance($tokenContract, $request->input('address'));

            return response()->json([
                'success' => true,
                'data' => [
                    'address' => $request->input('address'),
                    'balance' => $balance,
                    'token_contract' => $tokenContract,
                    'network' => $network
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting token balance', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get token balance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get token allowance
     */
    public function getAllowance(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'owner_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'spender_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $web3Service = new Web3Service($network);

            // Get contract address
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            if (!File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract config not found'
                ], 404);
            }

            $config = json_decode(File::get($contractsPath), true);
            $tokenContract = $config['contracts']['LENSToken'] ?? null;

            if (!$tokenContract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token contract address not found'
                ], 404);
            }

            $allowance = $web3Service->getTokenAllowance(
                $tokenContract,
                $request->input('owner_address'),
                $request->input('spender_address')
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'owner_address' => $request->input('owner_address'),
                    'spender_address' => $request->input('spender_address'),
                    'allowance' => $allowance,
                    'token_contract' => $tokenContract,
                    'network' => $network
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting token allowance', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get token allowance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare transfer token transaction
     */
    public function prepareTransfer(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token_contract' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'to_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'amount' => 'required|string',
                'from_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $web3Service = new Web3Service($network);
            $transactionService = new TransactionService($web3Service);

            // Function selector: transfer(address,uint256)
            // Keccak256("transfer(address,uint256)") = 0xa9059cbb
            $functionSelector = '0xa9059cbb';

            // Encode parameters
            $toParam = strtolower($request->input('to_address'));
            if (strpos($toParam, '0x') === 0) {
                $toParam = substr($toParam, 2);
            }
            $toParam = str_pad($toParam, 64, '0', STR_PAD_LEFT);

            $amountWei = $web3Service->etherToWei($request->input('amount'));
            $amountParam = str_replace('0x', '', $amountWei);
            $amountParam = str_pad($amountParam, 64, '0', STR_PAD_LEFT);

            $data = $functionSelector . $toParam . $amountParam;

            // Get nonce and gas
            $nonce = $web3Service->getNonce($request->input('from_address'));
            $gasPrice = $web3Service->getGasPrice();

            try {
                $gasLimit = $web3Service->estimateGas([
                    'from' => $request->input('from_address'),
                    'to' => $request->input('token_contract'),
                    'data' => $data
                ]);
            } catch (\Exception $e) {
                $gasLimit = '0x186a0'; // 100000
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction prepared successfully',
                'data' => [
                    'transaction' => [
                        'to' => $request->input('token_contract'),
                        'from' => $request->input('from_address'),
                        'data' => $data,
                        'value' => '0x0',
                        'gas' => $gasLimit,
                        'gasPrice' => $gasPrice,
                        'nonce' => '0x' . dechex($nonce),
                        'chainId' => $web3Service->getChainId()
                    ],
                    'instructions' => [
                        'step1' => 'Sign transaction on frontend',
                        'step2' => 'Send signed transaction using /api/transaction/send'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error preparing transfer transaction', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to prepare transfer transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get token contract info
     */
    public function getContractInfo(Request $request): JsonResponse
    {
        try {
            $network = $request->input('network', 'sepolia');
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            
            if (!File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract config not found'
                ], 404);
            }

            $config = json_decode(File::get($contractsPath), true);

            return response()->json([
                'success' => true,
                'data' => [
                    'contract_address' => $config['contracts']['LENSToken'] ?? null,
                    'abi' => $config['abis']['LENSToken'] ?? [],
                    'chain_id' => $config['chainId'] ?? 11155111,
                    'network' => $network,
                    'token_info' => [
                        'name' => 'LensArt Token',
                        'symbol' => 'LENS',
                        'decimals' => 18
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting token contract info', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get token contract info',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

