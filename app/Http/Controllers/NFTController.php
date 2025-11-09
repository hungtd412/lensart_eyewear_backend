<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Services\Web3Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class NFTController extends Controller
{
    /**
     * Get NFT contract info
     */
    public function getContractInfo(Request $request): JsonResponse
    {
        try {
            $network = $request->input('network', 'sepolia');
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            
            if (!File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract config not found for network: ' . $network
                ], 404);
            }

            $config = json_decode(File::get($contractsPath), true);

            return response()->json([
                'success' => true,
                'data' => [
                    'contract_address' => $config['contracts']['LensArtOrderNFT'] ?? null,
                    'abi' => $config['abis']['LensArtOrderNFT'] ?? [],
                    'chain_id' => $config['chainId'] ?? 11155111,
                    'network' => $network
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting NFT contract info', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get NFT contract info',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare mint NFT transaction
     */
    public function prepareMint(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nft_contract' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'to_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'order_id' => 'required|integer|min:1',
                'ipfs_hash' => 'required|string',
                'from_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $web3Service = new Web3Service($network);
            $transactionService = new TransactionService($web3Service);

            // Get nonce and gas price
            $nonce = $web3Service->getNonce($request->input('from_address'));
            $gasPrice = $web3Service->getGasPrice();

            return response()->json([
                'success' => true,
                'message' => 'Transaction prepared successfully',
                'data' => [
                    'transaction' => [
                        'to' => $request->input('nft_contract'),
                        'from' => $request->input('from_address'),
                        'value' => '0x0',
                        'gasPrice' => $gasPrice,
                        'nonce' => '0x' . dechex($nonce),
                        'chainId' => $web3Service->getChainId(),
                        'function' => 'mintOrderNFT',
                        'params' => [
                            'to' => $request->input('to_address'),
                            'orderId' => $request->input('order_id'),
                            'ipfsHash' => $request->input('ipfs_hash')
                        ]
                    ],
                    'instructions' => [
                        'step1' => 'Encode function call: contract.interface.encodeFunctionData("mintOrderNFT", [toAddress, orderId, ipfsHash])',
                        'step2' => 'Add encoded data to transaction.data',
                        'step3' => 'Sign and send transaction (only owner can mint)'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error preparing mint NFT transaction', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to prepare mint transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get NFT info by token ID
     */
    public function getNFTInfo(Request $request, int $tokenId): JsonResponse
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
            $nftContract = $config['contracts']['LensArtOrderNFT'] ?? null;

            if (!$nftContract) {
                return response()->json([
                    'success' => false,
                    'message' => 'NFT contract address not found'
                ], 404);
            }

            // Function: getOrderNFT(uint256)
            // Selector: first 4 bytes of keccak256("getOrderNFT(uint256)")
            // Note: Frontend should call this function directly using ethers.js
            
            return response()->json([
                'success' => true,
                'data' => [
                    'token_id' => $tokenId,
                    'contract_address' => $nftContract,
                    'network' => $network,
                    'instructions' => [
                        'call_function' => 'contract.getOrderNFT(tokenId)',
                        'returns' => [
                            'orderId' => 'uint256',
                            'ipfsHash' => 'string',
                            'mintedAt' => 'uint256'
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting NFT info', [
                'token_id' => $tokenId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get NFT info',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get NFTs owned by address
     */
    public function getOwnerNFTs(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'owner_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
            
            if (!File::exists($contractsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract config not found'
                ], 404);
            }

            $config = json_decode(File::get($contractsPath), true);
            $nftContract = $config['contracts']['LensArtOrderNFT'] ?? null;

            if (!$nftContract) {
                return response()->json([
                    'success' => false,
                    'message' => 'NFT contract address not found'
                ], 404);
            }

            // Note: Frontend should query contract for NFTs
            // ERC721 doesn't have a standard way to get all tokens by owner
            // Need to use events or indexer
            
            return response()->json([
                'success' => true,
                'data' => [
                    'owner_address' => $request->input('owner_address'),
                    'contract_address' => $nftContract,
                    'network' => $network,
                    'instructions' => [
                        'note' => 'Use contract events or indexer to get all NFTs owned by address',
                        'events' => 'OrderNFTMinted event contains tokenId and owner information',
                        'frontend_code' => 'Query events: contract.queryFilter(contract.filters.OrderNFTMinted(null, null, ownerAddress))'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting owner NFTs', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get owner NFTs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get token ID by order ID
     */
    public function getTokenIdByOrder(Request $request, int $orderId): JsonResponse
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
            $nftContract = $config['contracts']['LensArtOrderNFT'] ?? null;

            if (!$nftContract) {
                return response()->json([
                    'success' => false,
                    'message' => 'NFT contract address not found'
                ], 404);
            }

            // Function: getTokenIdByOrder(uint256)
            // Note: Frontend should call this function directly
            
            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $orderId,
                    'contract_address' => $nftContract,
                    'network' => $network,
                    'instructions' => [
                        'call_function' => 'contract.getTokenIdByOrder(orderId)',
                        'returns' => 'uint256 (token ID, 0 if not minted)'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting token ID by order', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get token ID',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

