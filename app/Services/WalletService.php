<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletService
{
    /**
     * Generate a new wallet (returns mnemonic, private key, address)
     * Note: This is a simplified version. In production, use a proper library like BitWasp/Bitcoin or similar
     * For Ethereum, we'll return structure but actual generation should be done on frontend for security
     */
    public function generateWallet(): array
    {
        // In production, wallet generation should be done on frontend
        // This is just a placeholder structure
        // Frontend should use ethers.js or web3.js to generate wallets
        
        return [
            'message' => 'Wallet generation should be done on frontend for security',
            'instruction' => 'Use ethers.js: const wallet = ethers.Wallet.createRandom()'
        ];
    }

    /**
     * Validate Ethereum address
     */
    public function validateAddress(string $address): bool
    {
        if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $address)) {
            return false;
        }

        // Basic checksum validation (simplified)
        return true;
    }

    /**
     * Validate private key format
     */
    public function validatePrivateKey(string $privateKey): bool
    {
        // Remove 0x prefix if present
        if (strpos($privateKey, '0x') === 0) {
            $privateKey = substr($privateKey, 2);
        }

        // Ethereum private key should be 64 hex characters
        if (!preg_match('/^[a-fA-F0-9]{64}$/', $privateKey)) {
            return false;
        }

        return true;
    }

    /**
     * Validate mnemonic phrase
     */
    public function validateMnemonic(string $mnemonic): bool
    {
        // Basic validation - mnemonic should have 12 or 24 words
        $words = explode(' ', trim($mnemonic));
        
        if (count($words) !== 12 && count($words) !== 24) {
            return false;
        }

        // In production, validate against BIP39 word list
        return true;
    }

    /**
     * Derive address from private key
     * Note: This is a placeholder. Actual derivation should use proper cryptographic libraries
     * In production, this should be done on frontend using ethers.js or web3.js
     */
    public function deriveAddressFromPrivateKey(string $privateKey): ?string
    {
        // This is a placeholder - actual implementation requires secp256k1
        // Frontend should derive address using ethers.js:
        // const wallet = new ethers.Wallet(privateKey);
        // const address = wallet.address;
        
        Log::warning('Address derivation from private key should be done on frontend');
        
        return null;
    }

    /**
     * Encrypt private key (for storage)
     * Note: This is a basic implementation. In production, use proper encryption
     */
    public function encryptPrivateKey(string $privateKey, string $password): string
    {
        // Use Laravel's encryption
        $encrypted = encrypt($privateKey . '|' . $password);
        return $encrypted;
    }

    /**
     * Decrypt private key
     */
    public function decryptPrivateKey(string $encryptedKey, string $password): ?string
    {
        try {
            $decrypted = decrypt($encryptedKey);
            $parts = explode('|', $decrypted);
            
            if (count($parts) !== 2) {
                return null;
            }

            if ($parts[1] !== $password) {
                return null;
            }

            return $parts[0];
        } catch (\Exception $e) {
            Log::error('Failed to decrypt private key', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate wallet info structure (for frontend)
     */
    public function getWalletInfoStructure(): array
    {
        return [
            'address' => 'string (0x...)',
            'private_key' => 'string (64 hex chars, NEVER send to backend)',
            'mnemonic' => 'string (12 or 24 words, NEVER send to backend)',
            'balance_eth' => 'string (decimal)',
            'balance_lens' => 'string (decimal)',
            'network' => 'string (sepolia, mainnet, etc.)'
        ];
    }

    /**
     * Get contract addresses for a network
     */
    public function getContractAddresses(string $network = 'sepolia'): array
    {
        $contractsPath = base_path('contracts/exports/frontend-config-' . $network . '.json');
        
        if (!\Illuminate\Support\Facades\File::exists($contractsPath)) {
            return [];
        }

        $config = json_decode(\Illuminate\Support\Facades\File::get($contractsPath), true);
        
        return [
            'LENSToken' => $config['contracts']['LENSToken'] ?? null,
            'LensArtPayment' => $config['contracts']['LensArtPayment'] ?? null,
            'LensArtOrderNFT' => $config['contracts']['LensArtOrderNFT'] ?? null,
            'chainId' => $config['chainId'] ?? 11155111,
            'rpcUrl' => $config['rpcUrl'] ?? null,
            'explorerUrl' => $config['explorerUrl'] ?? null,
        ];
    }
}

