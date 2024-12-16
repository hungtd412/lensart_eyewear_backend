<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use Faker\Factory as Faker;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Carbon\Carbon;

class BlogSeeder extends Seeder {
    public function run() {
        $faker = Faker::create();

        // Tạo 5 bài viết mẫu
        for ($i = 0; $i < 5; $i++) {
            // Lấy một hình ảnh ngẫu nhiên từ danh sách
            $sampleImages = $this->getSampleImages();
            // $selectedImage = $faker->randomElement($sampleImages);

            // Upload hình ảnh lên Cloudinary
            // $uploadedFile = Cloudinary::upload($selectedImage, [
            //     'folder' => 'blogs'
            // ]);

            // Tạo blog với dữ liệu mẫu
            Blog::create([
                'title' => $faker->sentence(6),
                'description' => $faker->paragraph(3),
                // 'image_url' => $uploadedFile->getSecurePath(),
                // 'image_public_id' => $uploadedFile->getPublicId(),
                'image_url' => 'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F03%2FANNA_AN086_5109-scaled.jpg&w=384&q=75',
                'image_public_id' => '$uploadedFile->getPublicId()',
                'created_time' => Carbon::now(),
                'status' => 'active',
            ]);
        }
    }

    // Hàm lấy danh sách hình ảnh mẫu
    public function getSampleImages() {
        return [
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F07%2Fz5682059395340_3510b82a2eea0e8fb15ff2a5c1965347.jpg&w=384&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F07%2Fz5682059329182_2603370bc20db65bcdb6b2ddd9ec2d87.jpg&w=384&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F07%2Fz5682059370910_6f1e0172f20a6011bf44186fff5e15f6.jpg&w=384&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F03%2FANNA_AN226825_6522.jpg&w=1080&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F03%2FANNA_AN2127_7053.jpg&w=384&q=75'
        ];
    }
}
