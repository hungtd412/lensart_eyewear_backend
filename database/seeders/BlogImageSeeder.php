<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class BlogImageSeeder extends Seeder
{
    public function run()
    {
        // Cấu hình Cloudinary
        Configuration::instance()->load([
            'cloud' => [
                'cloud_name' => env('CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ]
        ]);

        // Upload ảnh lên Cloudinary và lưu thông tin vào cơ sở dữ liệu
        $imagePath = public_path('images/sample-image.jpg'); // Đảm bảo file hình ảnh đã tồn tại tại đường dẫn này

        // Tải ảnh lên Cloudinary
        $uploadedImage = \Cloudinary\Uploader::upload($imagePath);

        DB::table('blogs_images')->insert([
            [
                'blog_id' => 1, // Gán blog_id cho ảnh này
                'image_url' => $uploadedImage['secure_url'], // Lấy URL ảnh từ Cloudinary
                'image_public_id' => $uploadedImage['public_id'], // Lấy public_id ảnh
            ],
            [
                'blog_id' => 2,
                'image_url' => $uploadedImage['secure_url'],
                'image_public_id' => $uploadedImage['public_id'],
            ],
            // Thêm các ảnh khác nếu cần
        ]);
    }
}
