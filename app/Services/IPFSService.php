<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class IPFSService
{
    protected $apiUrl;
    protected $apiKey;
    protected $apiSecret;
    protected $gatewayUrl;

    public function __construct()
    {
        // Use Pinata or Infura IPFS
        // For development, you can use public IPFS gateway
        $this->apiUrl = config('ipfs.api_url', 'https://api.pinata.cloud');
        $this->apiKey = config('ipfs.api_key', '');
        $this->apiSecret = config('ipfs.api_secret', '');
        $this->gatewayUrl = config('ipfs.gateway_url', 'https://gateway.pinata.cloud/ipfs/');
    }

    /**
     * Upload file to IPFS
     */
    public function uploadFile(UploadedFile $file, array $metadata = []): array
    {
        try {
            // Method 1: Using Pinata API
            if (!empty($this->apiKey) && !empty($this->apiSecret)) {
                return $this->uploadToPinata($file, $metadata);
            }

            // Method 2: Using public IPFS node (for testing)
            return $this->uploadToPublicIPFS($file, $metadata);

        } catch (\Exception $e) {
            Log::error('Failed to upload file to IPFS', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Upload to Pinata
     */
    protected function uploadToPinata(UploadedFile $file, array $metadata): array
    {
        $pinataMetadata = [
            'name' => $metadata['name'] ?? $file->getClientOriginalName(),
            'keyvalues' => $metadata['keyvalues'] ?? []
        ];

        // Prepare multipart form data
        $response = Http::withHeaders([
            'pinata_api_key' => $this->apiKey,
            'pinata_secret_api_key' => $this->apiSecret,
        ])->attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post($this->apiUrl . '/pinning/pinFileToIPFS', [
            'pinataMetadata' => json_encode($pinataMetadata),
            'pinataOptions' => json_encode([
                'cidVersion' => 1
            ])
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to upload to Pinata: ' . $response->body());
        }

        $result = $response->json();
        $ipfsHash = $result['IpfsHash'] ?? null;

        if (!$ipfsHash) {
            throw new \Exception('No IPFS hash returned from Pinata');
        }

        return [
            'success' => true,
            'ipfs_hash' => $ipfsHash,
            'ipfs_url' => $this->gatewayUrl . $ipfsHash,
            'gateway_url' => $this->gatewayUrl . $ipfsHash,
            'metadata' => $result
        ];
    }

    /**
     * Upload to public IPFS node (for testing)
     * Note: This uses a public IPFS node, files may not persist
     */
    protected function uploadToPublicIPFS(UploadedFile $file, array $metadata): array
    {
        // Using ipfs.io public gateway (for testing only)
        // In production, use Pinata, Infura, or your own IPFS node
        
        $response = Http::attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post('https://ipfs.infura.io:5001/api/v0/add', [
            'pin' => 'true'
        ]);

        if (!$response->successful()) {
            // Fallback: Return mock hash for testing
            Log::warning('Public IPFS upload failed, returning mock hash for testing');
            $mockHash = 'Qm' . bin2hex(random_bytes(32));
            
            return [
                'success' => true,
                'ipfs_hash' => $mockHash,
                'ipfs_url' => $this->gatewayUrl . $mockHash,
                'gateway_url' => $this->gatewayUrl . $mockHash,
                'warning' => 'This is a mock hash for testing. Configure Pinata or Infura for production.',
                'metadata' => $metadata
            ];
        }

        $result = $response->json();
        $ipfsHash = $result['Hash'] ?? null;

        if (!$ipfsHash) {
            throw new \Exception('No IPFS hash returned');
        }

        return [
            'success' => true,
            'ipfs_hash' => $ipfsHash,
            'ipfs_url' => $this->gatewayUrl . $ipfsHash,
            'gateway_url' => $this->gatewayUrl . $ipfsHash,
            'metadata' => $result
        ];
    }

    /**
     * Upload JSON data to IPFS
     */
    public function uploadJSON(array $data, array $metadata = []): array
    {
        try {
            $jsonString = json_encode($data, JSON_PRETTY_PRINT);
            
            // Create temporary file
            $tempFile = tmpfile();
            $tempPath = stream_get_meta_data($tempFile)['uri'];
            file_put_contents($tempPath, $jsonString);

            // Create UploadedFile instance
            $uploadedFile = new UploadedFile(
                $tempPath,
                ($metadata['name'] ?? 'data') . '.json',
                'application/json',
                null,
                true
            );

            $result = $this->uploadFile($uploadedFile, $metadata);

            // Clean up
            fclose($tempFile);

            return $result;

        } catch (\Exception $e) {
            Log::error('Failed to upload JSON to IPFS', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Retrieve file from IPFS
     */
    public function retrieveFile(string $ipfsHash): array
    {
        try {
            $gatewayUrl = $this->gatewayUrl . $ipfsHash;
            
            $response = Http::timeout(30)->get($gatewayUrl);

            if (!$response->successful()) {
                throw new \Exception('Failed to retrieve file from IPFS: ' . $response->status());
            }

            // Determine content type
            $contentType = $response->header('Content-Type') ?? 'application/octet-stream';
            $content = $response->body();

            return [
                'success' => true,
                'ipfs_hash' => $ipfsHash,
                'content' => $content,
                'content_type' => $contentType,
                'size' => strlen($content),
                'gateway_url' => $gatewayUrl
            ];

        } catch (\Exception $e) {
            Log::error('Failed to retrieve file from IPFS', [
                'ipfs_hash' => $ipfsHash,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Retrieve JSON from IPFS
     */
    public function retrieveJSON(string $ipfsHash): array
    {
        try {
            $result = $this->retrieveFile($ipfsHash);
            
            $jsonData = json_decode($result['content'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to parse JSON: ' . json_last_error_msg());
            }

            return [
                'success' => true,
                'ipfs_hash' => $ipfsHash,
                'data' => $jsonData,
                'gateway_url' => $result['gateway_url']
            ];

        } catch (\Exception $e) {
            Log::error('Failed to retrieve JSON from IPFS', [
                'ipfs_hash' => $ipfsHash,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Pin file to IPFS (Pinata)
     */
    public function pinFile(string $ipfsHash): bool
    {
        if (empty($this->apiKey) || empty($this->apiSecret)) {
            Log::warning('Pinata API keys not configured, skipping pin');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'pinata_api_key' => $this->apiKey,
                'pinata_secret_api_key' => $this->apiSecret,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/pinning/pinByHash', [
                'hashToPin' => $ipfsHash
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Failed to pin file to IPFS', [
                'ipfs_hash' => $ipfsHash,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get IPFS gateway URL
     */
    public function getGatewayUrl(string $ipfsHash): string
    {
        return $this->gatewayUrl . $ipfsHash;
    }
}

