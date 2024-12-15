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
            // 'username' => 'admin',
            'password' => Hash::make('123456'),
            'email' => 'admin@gmail.com',
            'email_verified_at' => Carbon::now(),
            'firstname' => $this->getRandomFirstName($faker),
            'lastname' => $this->getRandomLastName($faker),
            'address' => 'Vinhomes HCM',
            'created_time' => Carbon::now(), // Thêm giá trị created_time chính xác
            'role_id' => 1,
            'phone' => '0323456789',
        ]);

        DB::table('users')->insert([
            // 'username' => 'managerhcm',
            'password' => Hash::make('123456'),
            'email' => 'managerhcm@gmail.com',
            'email_verified_at' => Carbon::now(),
            'firstname' => $this->getRandomFirstName($faker),
            'lastname' => $this->getRandomLastName($faker),
            'address' => 'Vinhomes HCM',
            'created_time' => Carbon::now(), // Thêm giá trị created_time chính xác
            'role_id' => 2,
            'phone' => '0323456788',
        ]);

        DB::table('users')->insert([
            // 'username' => 'managerdn',
            'password' => Hash::make('123456'),
            'email' => 'managerdn@gmail.com',
            'email_verified_at' => Carbon::now(),
            'firstname' => $this->getRandomFirstName($faker),
            'lastname' => $this->getRandomLastName($faker),
            'address' => 'Vinhomes DN',
            'created_time' => Carbon::now(), // Thêm giá trị created_time chính xác
            'role_id' => 2,
            'phone' => '0323456787',
        ]);

        DB::table('users')->insert([
            // 'username' => 'managerhn',
            'password' => Hash::make('123456'),
            'email' => 'managerhn@gmail.com',
            'email_verified_at' => Carbon::now(),
            'firstname' => $this->getRandomFirstName($faker),
            'lastname' => $this->getRandomLastName($faker),
            'address' => 'Vinhomes HN',
            'created_time' => Carbon::now(), // Thêm giá trị created_time chính xác
            'role_id' => 2,
            'phone' => '0323456786',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $phone = $this->getRandomPhone($faker);
            $address = $this->getRandomAddress($faker);

            DB::table('users')->insert([
                // 'username' => 'customer' . $i,
                'password' => Hash::make('123456'),
                'email' => 'customer' . $i . '@gmail.com',
                'email_verified_at' => Carbon::now(),
                'firstname' => $this->getRandomFirstName($faker),
                'lastname' => $this->getRandomLastName($faker),
                'role_id' => 3,
                'phone' => $phone,
                'address' => $address,
                'created_time' => Carbon::now(), // Thêm giá trị created_time chính xác
            ]);
        }
    }

    public function getRandomFirstName($faker) {
        return $faker->randomElement([
            'Phạm',
            'Trần',
            'Nguyễn',
        ]);
    }

    public function getRandomLastName($faker) {
        return $faker->randomElement(['Đức Hùng', 'Quang Bảo', 'Minh Chính', 'Văn Thanh']);
    }

    public function getRandomPhone($faker) {
        $prefix = $faker->randomElement(['03', '05', '07', '08', '09']); // Select a random prefix
        return $prefix . $faker->numerify(str_repeat('#', 8));
    }

    public function getRandomAddress($faker) {
        return
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
                'Đường Pasteur',
            ])
            . ', '
            . $faker->randomElement(['Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ']);
    }
}
