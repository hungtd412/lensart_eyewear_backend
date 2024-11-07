<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('category')->insert([
            [
                'name' => 'Tròng kính',
                'description' => 'Các loại tròng kính cho các nhu cầu khác nhau như cận thị, viễn thị, chống ánh sáng xanh và bảo vệ mắt.',
            ],
            [
                'name' => 'Gọng kính',
                'description' => 'Gọng kính đa dạng kiểu dáng, chất liệu và màu sắc, giúp định hình và giữ tròng kính chắc chắn.',
            ],
            [
                'name' => 'Kính râm',
                'description' => 'Kính râm bảo vệ mắt khỏi ánh sáng mặt trời và tia UV, thiết kế thời trang và tiện dụng.',
            ],
        ]);
    }
}
