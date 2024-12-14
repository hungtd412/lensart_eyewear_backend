<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductImageRepositoryInterface;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductImageService {
    protected $productImageRepository;

    public function __construct(ProductImageRepositoryInterface $productImageRepository) {
        $this->productImageRepository = $productImageRepository;
    }

    public function store($data) {
        $uploadedFile = Cloudinary::upload($data['image']->getRealPath(), [
            'folder' => 'products'
        ]);

        $imageUrl = $uploadedFile->getSecurePath();
        $imagePublicId = $uploadedFile->getPublicId();

        $data = [
            'product_id' => $data['product_id'],
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
        ];

        $productImage = $this->productImageRepository->store($data);

        return response()->json([
            'status' => 'success',
            'data' => $productImage
        ], 200);
    }

    public function getAll() {
        $productImages = $this->productImageRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $productImages
        ], 200);
    }

    public function getById($id) {
        $productImage = $this->productImageRepository->getById($id);

        if ($productImage === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $productImage,
        ], 200);
    }

    public function getByProductId($productId) {
        $productImage = $this->productImageRepository->getByProductId($productId);

        if ($productImage === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $productImage,
        ], 200);
    }


    /*
    * Find image, then delete it and create a new one in cloudinary and update
    * image with new image_url, image_public_id
    */
    public function update($data, $id) {
        $productImage = $this->productImageRepository->getById($id);

        $uploadedFile = $this->updateCloudinaryAndGetNewFile($data['image'], $productImage);

        $data = $this->prepareDataForUpdate($data['product_id'], $uploadedFile);

        $this->productImageRepository->update($data, $productImage);

        return response()->json([
            'message' => 'success',
            'data' => $productImage
        ], 200);
    }

    public function updateCloudinaryAndGetNewFile($image, $productImage) {
        Cloudinary::destroy($productImage->image_public_id);

        return Cloudinary::upload($image->getRealPath(), [
            'folder' => 'products'
        ]);
    }

    public function prepareDataForUpdate($productId, $uploadedFile) {
        $data = [
            'product_id' => $productId,
            'image_url' => $uploadedFile->getSecurePath(),
            'image_public_id' => $uploadedFile->getPublicId(),
        ];

        return $data;
    }

    public function delete($id) {
        $productImage = $this->productImageRepository->getById($id);

        Cloudinary::destroy($productImage->image_public_id);

        $this->productImageRepository->delete($productImage);

        return response()->json([
            'message' => 'success',
            'data' => $productImage
        ], 200);
    }
}
