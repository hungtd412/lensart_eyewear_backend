<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {

        $faker = Faker::create();

        DB::table('users')->insert([
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'email' => 'admin@gmail.com',
            'address' => 'Vinhomes HCM',
            'role_id' => 1,
            'date_of_birth' => $faker->date('Y-m-d'),
            'phone' => '0323456789'
        ]);

        DB::table('users')->insert([
            'username' => 'managerhcm',
            'password' => Hash::make('123456'),
            'email' => 'managerhcm@gmail.com',
            'address' => 'Vinhomes HCM',
            'role_id' => 2,
            'date_of_birth' => $faker->date('Y-m-d'),
            'phone' => '0323456788'
        ]);

        DB::table('users')->insert([
            'username' => 'managerdn',
            'password' => Hash::make('123456'),
            'email' => 'managerdn@gmail.com',
            'address' => 'Vinhomes DN',
            'role_id' => 2,
            'date_of_birth' => $faker->date('Y-m-d'),
            'phone' => '0323456787'
        ]);

        DB::table('users')->insert([
            'username' => 'managerhn',
            'password' => Hash::make('123456'),
            'email' => 'managerhn@gmail.com',
            'address' => 'Vinhomes HN',
            'role_id' => 2,
            'date_of_birth' => $faker->date('Y-m-d'),
            'phone' => '0323456786'
        ]);

        for ($i = 1; $i <= 10; $i++) {
            $prefix = $faker->randomElement(['03', '05', '07', '08', '09']); // Select a random prefix
            $number = $prefix . $faker->numerify(str_repeat('#', $faker->numberBetween(8, 9))); // Generate 8-9 more digits

            $address =
                $faker->numberBetween(1, 1000)
                . ' '
                . $faker->randomElement([
                    'Đường Lê Lợi',
                    'Đường Nguyễn Huệ',
                    'Đường Trần Hưng Đạo',
                    'Đường Hai Bà Trưng',
                    'Đường Phạm Ngũ Lão',
                    'Đường Điện Biên Phủ',
                    'Đường Lý Thái Tổ',
                    'Đường Nguyễn Trãi',
                    'Đường Cách Mạng Tháng Tám',
                    'Đường Pasteur'
                ])
                . ', '
                . $faker->randomElement(['Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ']);

            DB::table('users')->insert([
                'username' => 'customer' . $i,
                'password' => Hash::make('123456'), // Default password for all users
                'email' => 'customer' . $i . '@gmail.com',
                'role_id' => 3, // Default role_id as specified
                'date_of_birth' => $faker->date('Y-m-d', '2005-12-31'),
                'avatar' => $faker->imageUrl(200, 200, 'people'), // Random avatar URL
                'phone' => $number, // Phone number in correct format
                'address' => $address,
                'status' => $faker->randomElement(['active', 'inactive']),
                'created_at' => now(),
            ]);
        }
    }
}
