<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'username' => 'managerhcm',
        //     'password' => '123456',
        //     'email' => 'managerhcm@gmail.com',
        //     'address' => 'Vinhomes Ba Son',
        //     'role_id' => 2,
        //     'phone' => '0911838655'
        // ]);

        // We create admin, manager here

        DB::table('users')->insert([
            'username' => 'managerhn',
            'password' => Hash::make('123456'),
            'email' => 'managerhn@gmail.com',
            'address' => 'Vinhomes Hà Nội',
            'role_id' => 2,
            'phone' => '0338999555'
        ]);
    }
}
