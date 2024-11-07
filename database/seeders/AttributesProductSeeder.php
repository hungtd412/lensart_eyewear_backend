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
        $this->seedColor();
        $this->seedMaterial();
        $this->seedShape();
        $this->seedFeature();
    }

    public function seedBrand() {
        DB::table('brands')->insert([
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

    public function seedColor() {
        DB::table('colors')->insert([
            [
                'name' => 'Đỏ',
            ],
            [
                'name' => 'Xanh',
            ],
            [
                'name' => 'Xám',
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
                'name' => 'Đổi màu',
            ],
            [
                'name' => 'Lọc ánh sáng xanh',
            ],
            [
                'name' => 'Siêu mỏng',
            ],
        ]);
    }
}
