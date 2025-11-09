<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class TransactionService
{
    protected $web3Service;

    public function __construct(Web3Service $web3Service)
    {
        $this->web3Service = $web3Service;
    }

    /**
     * Prepare transaction data for approve token
     */
    public function prepareApproveTransaction(
        string $tokenContractAddress,
        string $spenderAddress,
        string $amount,
        string $fromAddress,
        string $network = 'sepolia'
    ): array
    {
        // Load contract ABI to get function signature
        $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
        
        if (!File::exists($contractsPath)) {
            throw new \Exception('Contract config not found for network: ' . $network);
        }

        $config = json_decode(File::get($contractsPath), true);
        $abi = $config['abis']['LENSToken'] ?? [];

        // Function selector: approve(address,uint256)
        // Keccak256("approve(address,uint256)") = 0x095ea7b3
        $functionSelector = '0x095ea7b3';

        // Encode spender address (32 bytes, padded)
        $spenderParam = strtolower($spenderAddress);
        if (strpos($spenderParam, '0x') === 0) {
            $spenderParam = substr($spenderParam, 2);
        }
        $spenderParam = str_pad($spenderParam, 64, '0', STR_PAD_LEFT);

        // Encode amount (uint256)
        $amountWei = $this->web3Service->etherToWei($amount);
        $amountParam = str_replace('0x', '', $amountWei);
        $amountParam = str_pad($amountParam, 64, '0', STR_PAD_LEFT);

        // Data: function selector + encoded parameters
        $data = $functionSelector . $spenderParam . $amountParam;

        // Get nonce
        $nonce = $this->web3Service->getNonce($fromAddress);
        $nonceHex = '0x' . dechex($nonce);

        // Get gas price
        $gasPrice = $this->web3Service->getGasPrice();

        // Estimate gas
        try {
            $gasLimit = $this->web3Service->estimateGas([
                'from' => $fromAddress,
                'to' => $tokenContractAddress,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            // Default gas limit for approve
            $gasLimit = '0x186a0'; // 100000
        }

        return [
            'to' => $tokenContractAddress,
            'from' => $fromAddress,
            'data' => $data,
            'value' => '0x0',
            'gas' => $gasLimit,
            'gasPrice' => $gasPrice,
            'nonce' => $nonceHex,
            'chainId' => $this->web3Service->getChainId(),
        ];
    }

    /**
     * Prepare transaction data for initiate payment
     */
    public function prepareInitiatePaymentTransaction(
        string $paymentContractAddress,
        int $orderId,
        string $amount,
        string $ipfsHash,
        string $fromAddress,
        string $network = 'sepolia'
    ): array
    {
        // Function selector: initiatePayment(uint256,uint256,string)
        // This requires encoding the function signature and parameters
        // For simplicity, we'll return the structure and let frontend handle encoding with ethers.js
        
        // Function selector calculation (first 4 bytes of keccak256)
        // initiatePayment(uint256,uint256,string)
        $functionSelector = '0x'; // Will be calculated by frontend using ethers.js

        // Get nonce
        $nonce = $this->web3Service->getNonce($fromAddress);
        $nonceHex = '0x' . dechex($nonce);

        // Get gas price
        $gasPrice = $this->web3Service->getGasPrice();

        return [
            'to' => $paymentContractAddress,
            'from' => $fromAddress,
            'value' => '0x0',
            'gasPrice' => $gasPrice,
            'nonce' => $nonceHex,
            'chainId' => $this->web3Service->getChainId(),
            'function' => 'initiatePayment',
            'params' => [
                'orderId' => $orderId,
                'amount' => $amount,
                'ipfsHash' => $ipfsHash
            ],
            'note' => 'Frontend should use ethers.js to encode function call: contract.interface.encodeFunctionData("initiatePayment", [orderId, amount, ipfsHash])'
        ];
    }

    /**
     * Send signed transaction
     */
    public function sendSignedTransaction(string $signedTransaction, string $network = 'sepolia'): array
    {
        try {
            // Create new Web3Service instance for the network
            $web3Service = new Web3Service($network);
            
            // Send transaction
            $txHash = $web3Service->sendRawTransaction($signedTransaction);

            return [
                'success' => true,
                'transaction_hash' => $txHash,
                'explorer_url' => $this->getExplorerUrl($txHash, $network)
            ];
        } catch (\Exception $e) {
            Log::error('Failed to send transaction', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus(string $txHash, string $network = 'sepolia'): array
    {
        try {
            $web3Service = new Web3Service($network);
            
            $transaction = $web3Service->getTransaction($txHash);
            $receipt = $web3Service->getTransactionReceipt($txHash);

            if (!$transaction) {
                return [
                    'success' => false,
                    'status' => 'not_found',
                    'message' => 'Transaction not found'
                ];
            }

            if (!$receipt) {
                return [
                    'success' => true,
                    'status' => 'pending',
                    'transaction' => $transaction,
                    'message' => 'Transaction is pending'
                ];
            }

            // Check if transaction failed
            $status = $receipt['status'] ?? '0x0';
            $isSuccess = $status === '0x1';

            return [
                'success' => true,
                'status' => $isSuccess ? 'confirmed' : 'failed',
                'transaction' => $transaction,
                'receipt' => $receipt,
                'block_number' => isset($receipt['blockNumber']) ? hexdec($receipt['blockNumber']) : null,
                'gas_used' => isset($receipt['gasUsed']) ? hexdec($receipt['gasUsed']) : null,
                'explorer_url' => $this->getExplorerUrl($txHash, $network)
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get transaction status', [
                'tx_hash' => $txHash,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get explorer URL
     */
    protected function getExplorerUrl(string $txHash, string $network): string
    {
        $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
        
        if (!File::exists($contractsPath)) {
            return '';
        }

        $config = json_decode(File::get($contractsPath), true);
        $explorerUrl = $config['explorerUrl'] ?? '';

        if (empty($explorerUrl)) {
            return '';
        }

        // Replace {txHash} placeholder if exists
        return str_replace('{txHash}', $txHash, $explorerUrl . '/tx/' . $txHash);
    }

    /**
     * Read contract data (call view function)
     */
    public function readContractData(
        string $contractAddress,
        string $functionData,
        string $network = 'sepolia'
    ): string
    {
        try {
            $web3Service = new Web3Service($network);
            return $web3Service->callContractFunction($contractAddress, $functionData);
        } catch (\Exception $e) {
            Log::error('Failed to read contract data', [
                'contract' => $contractAddress,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

