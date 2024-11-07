<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('branches')->insert([
            "name" => "Hồ Chí Minh",
            "address" => "123 Nguyễn Trãi, Quận 1, TP. Hồ Chí Minh",
            "manager_id" => 2
        ]);
        DB::table('branches')->insert([
            "name" => "Đà Nẵng",
            "address" => "456 Trần Phú, Quận Hải Châu, TP. Đà Nẵng",
            "manager_id" => 3
        ]);
        DB::table('branches')->insert([
            "name" => "Hà Nội",
            "address" => "789 Đường Láng, Quận Đống Đa, TP. Hà Nội",
            "manager_id" => 4
        ]);
    }
}
