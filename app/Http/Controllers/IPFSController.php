<?php

namespace App\Http\Controllers;

use App\Services\IPFSService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IPFSController extends Controller
{
    protected $ipfsService;

    public function __construct(IPFSService $ipfsService)
    {
        $this->ipfsService = $ipfsService;
        parent::__construct();
    }

    /**
     * Upload file to IPFS
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:10240', // Max 10MB
                'name' => 'nullable|string|max:255',
                'metadata' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            $metadata = [
                'name' => $request->input('name', $file->getClientOriginalName()),
                'keyvalues' => $request->input('metadata', [])
            ];

            $result = $this->ipfsService->uploadFile($file, $metadata);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded to IPFS successfully',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading file to IPFS', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file to IPFS',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload JSON data to IPFS
     */
    public function uploadJSON(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array',
                'name' => 'nullable|string|max:255',
                'metadata' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $metadata = [
                'name' => $request->input('name', 'data.json'),
                'keyvalues' => $request->input('metadata', [])
            ];

            $result = $this->ipfsService->uploadJSON($request->input('data'), $metadata);

            return response()->json([
                'success' => true,
                'message' => 'JSON data uploaded to IPFS successfully',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading JSON to IPFS', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload JSON to IPFS',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve file from IPFS
     */
    public function retrieve(Request $request, string $hash): JsonResponse
    {
        try {
            $result = $this->ipfsService->retrieveFile($hash);

            // Determine if it's JSON
            $contentType = $result['content_type'] ?? '';
            if (strpos($contentType, 'application/json') !== false || 
                strpos($contentType, 'text/json') !== false) {
                
                $jsonResult = $this->ipfsService->retrieveJSON($hash);
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'ipfs_hash' => $hash,
                        'type' => 'json',
                        'data' => $jsonResult['data'],
                        'gateway_url' => $jsonResult['gateway_url']
                    ]
                ]);
            }

            // Return file content (base64 encoded for JSON response)
            return response()->json([
                'success' => true,
                'data' => [
                    'ipfs_hash' => $hash,
                    'type' => 'file',
                    'content_type' => $result['content_type'],
                    'size' => $result['size'],
                    'content' => base64_encode($result['content']),
                    'gateway_url' => $result['gateway_url'],
                    'note' => 'Content is base64 encoded. Decode on frontend to display.'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error retrieving file from IPFS', [
                'hash' => $hash,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve file from IPFS',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve JSON from IPFS
     */
    public function retrieveJSON(Request $request, string $hash): JsonResponse
    {
        try {
            $result = $this->ipfsService->retrieveJSON($hash);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Error retrieving JSON from IPFS', [
                'hash' => $hash,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve JSON from IPFS',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get IPFS gateway URL
     */
    public function getGatewayUrl(Request $request, string $hash): JsonResponse
    {
        try {
            $url = $this->ipfsService->getGatewayUrl($hash);

            return response()->json([
                'success' => true,
                'data' => [
                    'ipfs_hash' => $hash,
                    'gateway_url' => $url
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get gateway URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pin file to IPFS
     */
    public function pin(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ipfs_hash' => 'required|string'
            ]);

            $result = $this->ipfsService->pinFile($request->input('ipfs_hash'));

            return response()->json([
                'success' => $result,
                'message' => $result ? 'File pinned successfully' : 'Failed to pin file'
            ]);

        } catch (\Exception $e) {
            Log::error('Error pinning file to IPFS', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to pin file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

