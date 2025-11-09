<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Services\Web3Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
        parent::__construct();
    }

    /**
     * Prepare approve transaction
     */
    public function prepareApprove(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token_contract' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'spender_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'amount' => 'required|string',
                'from_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $web3Service = new Web3Service($network);
            $transactionService = new TransactionService($web3Service);

            $transaction = $transactionService->prepareApproveTransaction(
                $request->input('token_contract'),
                $request->input('spender_address'),
                $request->input('amount'),
                $request->input('from_address'),
                $network
            );

            return response()->json([
                'success' => true,
                'message' => 'Transaction prepared successfully',
                'data' => [
                    'transaction' => $transaction,
                    'instructions' => [
                        'step1' => 'Sign transaction on frontend using ethers.js or web3.js',
                        'step2' => 'Send signed transaction using /api/transaction/send endpoint',
                        'example' => 'const tx = await signer.signTransaction(transactionData);'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error preparing approve transaction', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to prepare transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prepare initiate payment transaction
     */
    public function prepareInitiatePayment(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'payment_contract' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'order_id' => 'required|integer|min:1',
                'amount' => 'required|string',
                'ipfs_hash' => 'nullable|string',
                'from_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $web3Service = new Web3Service($network);
            $transactionService = new TransactionService($web3Service);

            $transaction = $transactionService->prepareInitiatePaymentTransaction(
                $request->input('payment_contract'),
                $request->input('order_id'),
                $request->input('amount'),
                $request->input('ipfs_hash', ''),
                $request->input('from_address'),
                $network
            );

            return response()->json([
                'success' => true,
                'message' => 'Transaction prepared successfully',
                'data' => [
                    'transaction' => $transaction,
                    'instructions' => [
                        'step1' => 'Encode function call on frontend: contract.interface.encodeFunctionData("initiatePayment", [orderId, amount, ipfsHash])',
                        'step2' => 'Add encoded data to transaction.data',
                        'step3' => 'Sign and send transaction'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error preparing initiate payment transaction', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to prepare transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send signed transaction
     */
    public function sendTransaction(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'signed_transaction' => 'required|string',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $web3Service = new Web3Service($network);
            $transactionService = new TransactionService($web3Service);

            $result = $transactionService->sendSignedTransaction(
                $request->input('signed_transaction'),
                $network
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send transaction',
                    'error' => $result['error'] ?? 'Unknown error'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction sent successfully',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending transaction', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus(Request $request, string $txHash): JsonResponse
    {
        try {
            $request->validate([
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $web3Service = new Web3Service($network);
            $transactionService = new TransactionService($web3Service);

            $status = $transactionService->getTransactionStatus($txHash, $network);

            return response()->json([
                'success' => true,
                'data' => $status
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting transaction status', [
                'tx_hash' => $txHash,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get transaction status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Read contract data (call view function)
     */
    public function readContract(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'contract_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
                'function_data' => 'required|string',
                'network' => 'string|in:sepolia,mainnet'
            ]);

            $network = $request->input('network', 'sepolia');
            $web3Service = new Web3Service($network);
            $transactionService = new TransactionService($web3Service);

            $result = $transactionService->readContractData(
                $request->input('contract_address'),
                $request->input('function_data'),
                $network
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'result' => $result
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error reading contract', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to read contract',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

