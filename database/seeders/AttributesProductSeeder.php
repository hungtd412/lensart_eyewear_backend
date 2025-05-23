<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AttributesProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $this->seedBrand();
        $this->seedMaterial();
        $this->seedShape();
        $this->seedFeature();
    }

    public function seedBrand() {
        DB::table('brands')->insert([
            [
                'name' => 'LensArt',
            ],
            [
                'name' => 'Bevis',
            ],
            [
                'name' => 'Chemi',
            ],
            [
                'name' => 'Gucci',
            ],
        ]);
    }

    public function seedMaterial() {
        DB::table('materials')->insert([
            [
                'name' => 'Kim loại',
            ],
            [
                'name' => 'Nhựa',
            ],
            [
                'name' => 'Titan',
            ],
        ]);
    }

    public function seedShape() {
        DB::table('shapes')->insert([
            [
                'name' => 'Chữ nhật',
            ],
            [
                'name' => 'Đa giác',
            ],
            [
                'name' => 'Oval',
            ],
        ]);
    }

    public function seedFeature() {
        DB::table('features')->insert([
            [
                'name' => 'Siêu mỏng',
            ],
            [
                'name' => 'Chống ánh sáng xanh',
            ],
            [
                'name' => 'Đổi màu',
            ],
            [
                'name' => 'Cận phổ thông',
            ],
            [
                'name' => 'Chống tia UV',
            ],
            [
                'name' => 'Đa tròng',
            ],
            [
                'name' => 'Râm cận',
            ],
        ]);
    }
}
