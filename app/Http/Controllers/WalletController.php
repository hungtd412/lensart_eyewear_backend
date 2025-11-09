<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use App\Services\Web3Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    protected $walletService;
    protected $web3Service;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
        parent::__construct();
    }

    /**
     * Get wallet generation instructions
     * Note: Actual wallet generation should be done on frontend for security
     */
    public function getWalletInfo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Wallet generation instructions',
            'data' => [
                'instruction' => 'Wallet generation should be done on frontend using ethers.js or web3.js for security',
                'frontend_code' => [
                    'ethers_js' => 'const wallet = ethers.Wallet.createRandom();',
                    'web3_js' => 'const account = web3.eth.accounts.create();'
                ],
                'structure' => $this->walletService->getWalletInfoStructure()
            ]
        ]);
    }

    /**
     * Validate wallet address
     */
    public function validateAddress(Request $request): JsonResponse
    {
        $request->validate([
            'address' => 'required|string'
        ]);

        $address = $request->input('address');
        $isValid = $this->walletService->validateAddress($address);

        return response()->json([
            'success' => true,
            'data' => [
                'address' => $address,
                'is_valid' => $isValid
            ]
        ]);
    }

    /**
     * Validate private key format
     */
    public function validatePrivateKey(Request $request): JsonResponse
    {
        $request->validate([
            'private_key' => 'required|string'
        ]);

        $privateKey = $request->input('private_key');
        $isValid = $this->walletService->validatePrivateKey($privateKey);

        return response()->json([
            'success' => true,
            'data' => [
                'is_valid' => $isValid,
                'note' => 'Private key should NEVER be sent to backend in production. Validation should be done on frontend.'
            ]
        ]);
    }

    /**
     * Get wallet balance (ETH + LENS)
     */
    public function getBalance(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $address = $request->input('address');
            $network = $request->input('network', 'sepolia');

            // Validate address
            if (!$this->walletService->validateAddress($address)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid wallet address'
                ], 400);
            }

            // Get contract addresses
            $contracts = $this->walletService->getContractAddresses($network);
            
            if (empty($contracts['LENSToken'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract addresses not found for network: ' . $network
                ], 404);
            }

            // Get balances
            $web3Service = new Web3Service($network);
            $ethBalance = $web3Service->getEthBalance($address);
            $lensBalance = $web3Service->getTokenBalance($contracts['LENSToken'], $address);

            return response()->json([
                'success' => true,
                'data' => [
                    'address' => $address,
                    'network' => $network,
                    'balances' => [
                        'eth' => $ethBalance,
                        'lens' => $lensBalance
                    ],
                    'contracts' => [
                        'lens_token' => $contracts['LENSToken']
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting wallet balance', [
                'address' => $request->input('address'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get wallet balance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get contract addresses for network
     */
    public function getContractAddresses(Request $request): JsonResponse
    {
        $network = $request->input('network', 'sepolia');
        
        $contracts = $this->walletService->getContractAddresses($network);

        if (empty($contracts)) {
            return response()->json([
                'success' => false,
                'message' => 'Contract addresses not found for network: ' . $network
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    /**
     * Get contract ABIs
     */
    public function getContractABIs(Request $request): JsonResponse
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

            return response()->json([
                'success' => true,
                'data' => [
                    'LENSToken' => $config['abis']['LENSToken'] ?? [],
                    'LensArtPayment' => $config['abis']['LensArtPayment'] ?? [],
                    'LensArtOrderNFT' => $config['abis']['LensArtOrderNFT'] ?? [],
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting contract ABIs', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get contract ABIs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

