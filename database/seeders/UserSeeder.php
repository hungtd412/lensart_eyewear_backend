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
            'address' => '50 Đường Lý Thái Tổ, Thị trấn Đồng Văn, Huyện Duy Tiên, Hà Nam',
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
            'address' => '3569 Đường Phạm Văn Đồng, Phường Linh Đông, Thành phố Thủ Đức, Hồ Chí Minh',
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
            'address' => '123 Đường Nguyễn Văn Linh, Phường Hải Châu 1, Quận Hải Châu, Đà Nẵng',
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
            'address' => '80 Đường Láng Hạ, Phường Thành Công, Quận Ba Đình, Hà Nội',
            'created_time' => Carbon::now(), // Thêm giá trị created_time chính xác
            'role_id' => 2,
            'phone' => '0323456786',
        ]);

        for ($i = 1; $i <= 31; $i++) {
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
            'Đặng',
            'Hồ',
            'Hoàng',
            'Đào',
        ]);
    }

    public function getRandomLastName($faker) {
        return $faker->randomElement(['Đức Hùng', 'Quang Bảo', 'Minh Chính', 'Văn Thanh', 'Tiến Đạt', 'Quốc Khánh', 'Bình Minh', 'Ánh Sao', 'Phương Tuấn']);
    }

    public function getRandomPhone($faker) {
        $prefix = $faker->randomElement(['03', '05', '07', '08', '09']); // Select a random prefix
        return $prefix . $faker->numerify(str_repeat('#', 8));
    }

    public function getRandomAddress($faker) {
        $addresses = [
            '150 Đường Hùng Vương, Thị trấn Đông Hà, Huyện Cam Lộ, Quảng Trị',
            '50 Đường Lý Thái Tổ, Thị trấn Đồng Văn, Huyện Duy Tiên, Hà Nam',
            '3569 Đường Phạm Văn Đồng, Phường Linh Đông, Thành phố Thủ Đức, Hồ Chí Minh',
            '120 Đường Trần Phú, Phường Máy Chai, Quận Ngô Quyền, Hải Phòng',
            '20 Đường Trần Phú, Thị trấn Đức An, Huyện Đăk Song, Đắk Nông',
            '30 Đường Quang Trung, Thị trấn Krông Klang, Huyện Đakrông, Quảng Trị',
            '40 Đường Phan Bội Châu, Thị trấn Gia Nghĩa, Thành phố Gia Nghĩa, Đắk Nông',
            '2072 Đường Quách Thị Trang, Xã Vĩnh Thanh, Huyện Nhơn Trạch, Đồng Nai',
            '60 Đường Hoàng Diệu, Thị trấn Tân Phú, Huyện Tân Phú, Đồng Nai',
            '70 Đường Nguyễn Trãi, Thị trấn Phước Bửu, Huyện Xuyên Mộc, Bà Rịa - Vũng Tàu'
        ];
        //TDP1, Thị trấn An Phú, Huyện An Phú, An Giang

        return $faker->randomElement($addresses);
    }
}
