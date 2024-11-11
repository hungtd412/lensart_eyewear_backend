<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Faker\Factory as Faker;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductImageSeeder extends Seeder {
    public function run() {
        $faker = Faker::create();

        $cate1Products = Product::where('category_id', 1)->take(2)->get();
        $cate2Products = Product::where('category_id', 2)->take(3)->get();
        $cate3Products = Product::where('category_id', 3)->take(3)->get();

        $products = $cate1Products
            ->merge($cate2Products)
            ->merge($cate3Products);


        foreach ($products as $product) {
            $sampleImages = $this->getImagesByType($product->category_id);

            $uploadedFile = Cloudinary::upload($faker->randomElement($sampleImages), [
                'folder' => 'products'
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $uploadedFile->getSecurePath(),
                'image_public_id' => $uploadedFile->getPublicId(),
            ]);
        }
    }

    public function getImagesByType($type) {
        $trongKinhImages = [
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F07%2Fz5682059395340_3510b82a2eea0e8fb15ff2a5c1965347.jpg&w=384&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F07%2Fz5682059329182_2603370bc20db65bcdb6b2ddd9ec2d87.jpg&w=384&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F07%2Fz5682059370910_6f1e0172f20a6011bf44186fff5e15f6.jpg&w=384&q=75',
        ];

        $gongKinhImages = [
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F03%2FANNA_AN226825_6522.jpg&w=1080&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F03%2FANNA_AN2127_7053.jpg&w=384&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F03%2FANNA_AN080_7723.jpg&w=384&q=75',
        ];

        $kinhRamImages = [
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F07%2FDMT09591-scaled.jpg&w=384&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F07%2FDMT09445-scaled.jpg&w=384&q=75',
            'https://kinhmatanna.com/_next/image?url=https%3A%2F%2Fcms.kinhmatanna.com%2Fwp-content%2Fuploads%2F2024%2F07%2FDMT09541-scaled.jpg&w=384&q=75',
        ];

        switch ($type) {
            case 1:
                return $trongKinhImages;
            case 2:
                return $gongKinhImages;
            case 3:
                return $kinhRamImages;
            default:
                return [];
        }
    }
}
