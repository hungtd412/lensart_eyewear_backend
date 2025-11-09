<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class Web3Service
{
    protected $rpcUrl;
    protected $chainId;

    public function __construct($network = 'sepolia')
    {
        $this->loadNetworkConfig($network);
    }

    /**
     * Load network configuration
     */
    protected function loadNetworkConfig($network)
    {
        $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
        
        if (!File::exists($contractsPath)) {
            throw new \Exception('Contract config not found for network: ' . $network);
        }

        $config = json_decode(File::get($contractsPath), true);
        $this->rpcUrl = $config['rpcUrl'] ?? null;
        $this->chainId = $config['chainId'] ?? 11155111;

        if (!$this->rpcUrl) {
            throw new \Exception('RPC URL not found for network: ' . $network);
        }
    }

    /**
     * Call RPC method
     */
    public function callRPC(string $method, array $params = [], int $id = 1): array
    {
        $requestData = [
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
            'id' => $id
        ];

        try {
            $response = Http::timeout(30)
                ->withoutVerifying()
                ->post($this->rpcUrl, $requestData);

            if (!$response->successful()) {
                Log::error('RPC call failed', [
                    'method' => $method,
                    'http_code' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('RPC call failed: ' . $response->body());
            }

            $result = $response->json();

            if (isset($result['error'])) {
                Log::error('RPC error', [
                    'method' => $method,
                    'error' => $result['error']
                ]);
                throw new \Exception('RPC error: ' . ($result['error']['message'] ?? 'Unknown error'));
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Exception in RPC call', [
                'method' => $method,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get ETH balance
     */
    public function getEthBalance(string $address): string
    {
        $result = $this->callRPC('eth_getBalance', [$address, 'latest']);
        
        if (!isset($result['result'])) {
            return '0';
        }

        return $this->weiToEther($result['result']);
    }

    /**
     * Get token balance
     */
    public function getTokenBalance(string $contractAddress, string $userAddress): string
    {
        // Function selector: balanceOf(address)
        $functionSelector = '0x70a08231';
        
        // Encode address parameter
        $addressParam = strtolower($userAddress);
        if (strpos($addressParam, '0x') === 0) {
            $addressParam = substr($addressParam, 2);
        }
        $addressParam = str_pad($addressParam, 64, '0', STR_PAD_LEFT);
        
        // Data: function selector + encoded address
        $data = $functionSelector . $addressParam;
        
        $result = $this->callRPC('eth_call', [[
            'to' => $contractAddress,
            'data' => $data
        ], 'latest']);

        if (!isset($result['result'])) {
            return '0';
        }

        $balanceHex = $result['result'];
        if ($balanceHex === '0x' || empty($balanceHex)) {
            return '0';
        }

        return $this->weiToEther($balanceHex);
    }

    /**
     * Get token allowance
     */
    public function getTokenAllowance(string $contractAddress, string $owner, string $spender): string
    {
        // Function selector: allowance(address,address)
        $functionSelector = '0xdd62ed3e';
        
        // Encode parameters
        $ownerParam = strtolower($owner);
        if (strpos($ownerParam, '0x') === 0) {
            $ownerParam = substr($ownerParam, 2);
        }
        $ownerParam = str_pad($ownerParam, 64, '0', STR_PAD_LEFT);
        
        $spenderParam = strtolower($spender);
        if (strpos($spenderParam, '0x') === 0) {
            $spenderParam = substr($spenderParam, 2);
        }
        $spenderParam = str_pad($spenderParam, 64, '0', STR_PAD_LEFT);
        
        // Data: function selector + encoded parameters
        $data = $functionSelector . $ownerParam . $spenderParam;
        
        $result = $this->callRPC('eth_call', [[
            'to' => $contractAddress,
            'data' => $data
        ], 'latest']);

        if (!isset($result['result'])) {
            return '0';
        }

        $allowanceHex = $result['result'];
        if ($allowanceHex === '0x' || empty($allowanceHex)) {
            return '0';
        }

        return $this->weiToEther($allowanceHex);
    }

    /**
     * Get transaction receipt
     */
    public function getTransactionReceipt(string $txHash): ?array
    {
        $result = $this->callRPC('eth_getTransactionReceipt', [$txHash]);
        
        if (!isset($result['result']) || $result['result'] === null) {
            return null;
        }

        return $result['result'];
    }

    /**
     * Get transaction by hash
     */
    public function getTransaction(string $txHash): ?array
    {
        $result = $this->callRPC('eth_getTransactionByHash', [$txHash]);
        
        if (!isset($result['result']) || $result['result'] === null) {
            return null;
        }

        return $result['result'];
    }

    /**
     * Get block number
     */
    public function getBlockNumber(): string
    {
        $result = $this->callRPC('eth_blockNumber');
        
        if (!isset($result['result'])) {
            return '0';
        }

        return hexdec($result['result']);
    }

    /**
     * Send raw transaction
     */
    public function sendRawTransaction(string $signedTransaction): string
    {
        $result = $this->callRPC('eth_sendRawTransaction', [$signedTransaction]);
        
        if (!isset($result['result'])) {
            throw new \Exception('Failed to send transaction');
        }

        return $result['result']; // Transaction hash
    }

    /**
     * Estimate gas
     */
    public function estimateGas(array $transaction): string
    {
        $result = $this->callRPC('eth_estimateGas', [$transaction]);
        
        if (!isset($result['result'])) {
            throw new \Exception('Failed to estimate gas');
        }

        return $result['result'];
    }

    /**
     * Get gas price
     */
    public function getGasPrice(): string
    {
        $result = $this->callRPC('eth_gasPrice');
        
        if (!isset($result['result'])) {
            return '0';
        }

        return $result['result'];
    }

    /**
     * Get nonce
     */
    public function getNonce(string $address): string
    {
        $result = $this->callRPC('eth_getTransactionCount', [$address, 'latest']);
        
        if (!isset($result['result'])) {
            return '0';
        }

        return hexdec($result['result']);
    }

    /**
     * Call contract function (read-only)
     */
    public function callContractFunction(string $contractAddress, string $data): string
    {
        $result = $this->callRPC('eth_call', [[
            'to' => $contractAddress,
            'data' => $data
        ], 'latest']);

        if (!isset($result['result'])) {
            return '0x';
        }

        return $result['result'];
    }

    /**
     * Convert wei to ether
     */
    protected function weiToEther(string $weiHex): string
    {
        if (strpos($weiHex, '0x') === 0) {
            $weiHex = substr($weiHex, 2);
        }
        
        // Convert hex to decimal using bcmath
        if (!function_exists('bcadd')) {
            $weiDecimal = (string)hexdec($weiHex);
        } else {
            $weiDecimal = '0';
            $length = strlen($weiHex);
            for ($i = 0; $i < $length; $i++) {
                $digit = hexdec($weiHex[$i]);
                $weiDecimal = bcmul($weiDecimal, '16', 0);
                $weiDecimal = bcadd($weiDecimal, (string)$digit, 0);
            }
        }
        
        // Divide by 10^18 to convert wei to ether
        $divisor = '1000000000000000000';
        
        if (function_exists('bcdiv')) {
            $ether = bcdiv($weiDecimal, $divisor, 18);
            $ether = rtrim(rtrim($ether, '0'), '.');
        } else {
            $weiInt = (float)$weiDecimal;
            $ether = (string)($weiInt / 1000000000000000000);
            $ether = rtrim(rtrim(sprintf('%.18f', $ether), '0'), '.');
        }
        
        return $ether ?: '0';
    }

    /**
     * Convert ether to wei
     */
    public function etherToWei(string $ether): string
    {
        if (!function_exists('bcmul')) {
            $wei = (string)(floatval($ether) * 1000000000000000000);
            return '0x' . dechex($wei);
        }

        $multiplier = '1000000000000000000';
        $wei = bcmul($ether, $multiplier, 0);
        return '0x' . $this->decToHex($wei);
    }

    /**
     * Convert decimal to hex
     */
    protected function decToHex(string $decimal): string
    {
        $hex = '';
        while ($decimal > '0') {
            $remainder = bcmod($decimal, '16');
            $hex = dechex((int)$remainder) . $hex;
            $decimal = bcdiv($decimal, '16', 0);
        }
        return $hex ?: '0';
    }

    /**
     * Get RPC URL
     */
    public function getRpcUrl(): string
    {
        return $this->rpcUrl;
    }

    /**
     * Get Chain ID
     */
    public function getChainId(): int
    {
        return $this->chainId;
    }
}

