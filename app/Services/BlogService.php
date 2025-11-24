<?php

namespace App\Services;

use App\Repositories\BlogReposityInterface;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BlogService {
    protected $blogRepository;

    public function __construct(BlogReposityInterface $blogRepository) {
        $this->blogRepository = $blogRepository;
    }

    public function store($data) {

        $uploadedFile = Cloudinary::upload($data['image']->getRealPath(), [
            'folder' => 'blogs'
        ]);

        $imageUrl = $uploadedFile->getSecurePath();
        $imagePublicId = $uploadedFile->getPublicId();

        $data = [
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'] ?? '',
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
            'created_time' => Carbon::now('Asia/Ho_Chi_Minh'),
        ];

        $blog = $this->blogRepository->store($data);

        return response()->json([
            'status' => 'success',
            'data' => $blog
        ], 200);
    }

    public function getAll() {
        $blogs = $this->blogRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $blogs
        ], 200);
    }

    public function getById($id) {
        $blog = $this->blogRepository->getById($id);

        if ($blog === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $blog,
        ], 200);
    }

    public function update($data, $id) {
        $blog = $this->blogRepository->getById($id);

        $uploadedFile = $this->updateCloudinaryAndGetNewFile($data['image'], $blog);

        $data = [
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'] ?? $blog->content ?? '',
            'image_url' => $uploadedFile->getSecurePath(),
            'image_public_id' => $uploadedFile->getPublicId(),
        ];

        $this->blogRepository->update($data, $blog);

        return response()->json([
            'message' => 'success',
            'data' => $blog
        ], 200);
    }

    public function updateCloudinaryAndGetNewFile($image, $productImage) {
        Cloudinary::destroy($productImage->image_public_id);

        return Cloudinary::upload($image->getRealPath(), [
            'folder' => 'blogs'
        ]);
    }

    public function switchStatus($id) {
        $blog = $this->blogRepository->getById($id);

        $this->blogRepository->switchStatus($blog);

        return response()->json([
            'message' => 'success',
            'data' => $blog
        ], 200);
    }

    public function delete($id) {
        $blog = $this->blogRepository->getById($id);

        $this->blogRepository->delete($blog);

        return response()->json([
            'message' => 'success',
        ], 200);
    }

    /**
     * Lấy danh sách các blog có trạng thái 'active'
     *
     * @param int $limit
     * @return mixed
     */
    public function getActiveBlogs($limit = 10) {
        return $this->blogRepository->getAllActive($limit);
    }
}
