<?php

namespace App\Services;

use App\Repositories\BannerReposityInterface;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BannerService {
    protected $bannerRepository;

    public function __construct(BannerReposityInterface $bannerRepository) {
        $this->bannerRepository = $bannerRepository;
    }

    public function store($data) {
        if (!isset($data['image']) || !$data['image']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Image is required when creating a banner'
            ], 400);
        }

        $uploadedFile = Cloudinary::upload($data['image']->getRealPath(), [
            'folder' => 'banners'
        ]);

        $imageUrl = $uploadedFile->getSecurePath();
        $imagePublicId = $uploadedFile->getPublicId();

        $bannerData = [
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
            'status' => $data['status'] ?? 'active',
        ];

        $banner = $this->bannerRepository->store($bannerData);

        return response()->json([
            'status' => 'success',
            'message' => 'Banner created successfully',
            'data' => $banner
        ], 200);
    }

    public function get() {
        $banner = $this->bannerRepository->get();

        return response()->json([
            'status' => 'success',
            'data' => $banner
        ], 200);
    }

    public function getActive() {
        $banner = $this->bannerRepository->getActive();

        return response()->json([
            'status' => 'success',
            'data' => $banner
        ], 200);
    }

    public function update($data) {
        $banner = $this->bannerRepository->get();

        if ($banner) {
            $updateData = [];
            
            // Only update image if a new one is provided
            if (isset($data['image']) && $data['image']) {
                $uploadedFile = $this->updateCloudinaryAndGetNewFile($data['image'], $banner);
                $updateData['image_url'] = $uploadedFile->getSecurePath();
                $updateData['image_public_id'] = $uploadedFile->getPublicId();
            }
            
            // Update status if provided
            if (isset($data['status'])) {
                $updateData['status'] = $data['status'];
            }

            $this->bannerRepository->update($updateData, $banner);
            
            // Refresh banner to get updated data
            $banner = $this->bannerRepository->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Banner updated successfully',
                'data' => $banner
            ], 200);
        } else {
            // If no banner exists and image is provided, create new one
            if (isset($data['image']) && $data['image']) {
                return $this->store($data);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'No banner exists and no image provided'
            ], 400);
        }
    }

    public function updateCloudinaryAndGetNewFile($image, $productImage) {
        try {
            if (!is_null($productImage->image_public_id)) {
                Cloudinary::destroy($productImage->image_public_id);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }


        return Cloudinary::upload($image->getRealPath(), [
            'folder' => 'banners'
        ]);
    }

    public function switchStatus() {
        $banner = $this->bannerRepository->get();

        $this->bannerRepository->switchStatus($banner);

        return response()->json([
            'message' => 'success',
            'data' => $banner
        ], 200);
    }
}
