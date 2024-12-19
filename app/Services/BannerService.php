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

        $uploadedFile = Cloudinary::upload($data['image']->getRealPath(), [
            'folder' => 'banners'
        ]);

        $imageUrl = $uploadedFile->getSecurePath();
        $imagePublicId = $uploadedFile->getPublicId();

        $data = [
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
        ];

        $banner = $this->bannerRepository->store($data);

        return response()->json([
            'status' => 'success',
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

        $uploadedFile = $this->updateCloudinaryAndGetNewFile($data['image'], $banner);

        $data = [
            'image_url' => $uploadedFile->getSecurePath(),
            'image_public_id' => $uploadedFile->getPublicId(),
            'status' => $data['status'],
        ];

        $this->bannerRepository->update($data, $banner);

        return response()->json([
            'message' => 'success',
            'data' => $banner
        ], 200);
    }

    public function updateCloudinaryAndGetNewFile($image, $productImage) {
        Cloudinary::destroy($productImage->image_public_id);

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
