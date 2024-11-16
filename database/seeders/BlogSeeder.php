<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    public function run()
    {
        DB::table('blogs')->insert([
            [
                'title' => 'First Blog Post',
                'description' => 'This is the first blog post.',
                'status' => 'active',
                'created_time' => Carbon::now(),
            ],
            [
                'title' => 'Second Blog Post',
                'description' => 'This is the second blog post.',
                'status' => 'active',
                'created_time' => Carbon::now(),
            ],
            // Thêm các bài viết khác nếu cần
        ]);
    }
}
